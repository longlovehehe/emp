<?php
    /*
    *EMP单点登录功能文件
    *$ticket 鉴权标识字段
    *author yuejun.wang
    */
    
    /*错误级别、时区设置*/
    header("Content-type:text/html;charset=utf-8");
    error_reporting(E_ERROR);
    ini_set('display_errors', '1');
    date_default_timezone_set('PRC');
    ini_set('date.timezone', 'Asia/Shanghai');
    /*包含必要的文件*/
    require_once "/usr/local/opa/www/html/webservice/nusoap/nusoap.php";
    require_once "/usr/local/opa/www/html/webservice/writefile.php";
    require_once "/usr/local/opa/www/html/webservice/saas_public.php";
    
    $host = $_SERVER['HTTP_HOST'];  /*获取请求服务器的地址*/
    $loginAuthPath=getAuthAddr();   /*获取请求对方的接口文件路径*/
    $SubmitTime = getTime();    /*请求时间*/

    if(!isset($_GET['t']) || empty($_GET['t'])){
        alertMsg(1);
        die;
    }else{
        $ticket = trim($_GET['t']);
        writeFile($file, "应用鉴权ticket:".$ticket, 'a');
    }

    $Ctid = getCtid();          /* 生成客户端交易序列号 */
    $Appid = getAppId();        /* 获取指定的appid */
      
    //生成xml数据
    $submit_xml='<SIID></SIID>
        <AppID>'.$Appid.'</AppID>
        <Ticket>'.$ticket.'</Ticket>';
    //加密body体
    $encode = safe($submit_xml,'en');
    $result_xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Msg><Head>
        <Code>SYS00001</Code>
        <CTID>'.$Ctid.'</CTID>
        <AppID>'.$Appid.'</AppID>
        <SubmitTime>'.$SubmitTime.'</SubmitTime>
        <Version>1</Version>
        <Priority>10</Priority></Head>
        <Body>'.$encode.'</Body>
        </Msg>';
    /*记录日志文件*/
    writeFile($file, "自动登录(isoftstone)xml:".$result_xml, 'a');

    /*请求软通的接口获取返回的XML数据*/
    $client = new nusoap_client($loginAuthPath, true);
    $client->soap_defencoding = 'UTF-8';
    $client->decode_utf8 = false;
    $client->xml_encoding = 'UTF-8';
    $parameters = array(array('requestXML' => $result_xml));
    $back_xml = $client->call('execute', $parameters);
    writeFile($file, "登录鉴权返回的xml:".$back_xml['executeReturn'], 'a');
    if ($err=$client->getError()) {
        writeFile($file, "登录鉴权返回错误:".$err, 'a');
        alertMsg(2);
        die;
    }
    if(empty($back_xml['executeReturn']) || $back_xml['executeReturn']==''){
        alertMsg(7);
        die;
    } 
    /*-------请求结束---------*/

    $p = xml_parser_create();
    xml_parse_into_struct($p, $back_xml['executeReturn'], $value);
    xml_parser_free($p);
    $encrypt_xml = '';
    foreach($value as $v) {
        if($v['tag'] == 'BODY') {
            $encrypt_xml = $v['value'];
        }
    }
    //解body加密体
    $xmlStr = safe($encrypt_xml,'de');
    //记录日志
    writeFile($file, "登录鉴权返回后解密好的消息体xml:".$xmlStr, 'a');

    $xmlArgs = '<BODY>'.$xmlStr.'</BODY>';
    //解析body部分的XML
    $args = xml_parser_create();
    xml_parse_into_struct($args, $xmlArgs, $val);
    xml_parser_free($args);
    foreach($val as $v) {
        if($v['tag'] == 'RESULTCODE') 
            $resultCode = $v['value'];
        if($v['tag'] == 'ECID') 
            $ecId = $v['value'];
        if($v['tag'] == 'USERTYPE') 
            $UserType = $v['value'];
        if($v['tag'] == 'ECMEMBERID') 
            $EcMemberID = $v['value'];
        if($v['tag'] == 'USERNAME') 
            $UserName = $v['value'];
    }
    
    //成功返回0，失败返回-1
    if($resultCode == '0000') {
        if($UserType=='0'){
            $pdo = new db();
            //获取企业信息，判断并存session
            $res = $pdo->checkLogin($ecId);
            if($res == -1){
                alertMsg(3);
                die; 
            }else{
                header("Location: http://".$host."/emp/?m=enterprise&a=view");
                die;
            }
            header("Location: http://".$host."/emp/?m=enterprise&a=view");
            die;
        }else{
            alertMsg(5);
            exit();
        }
    } else {
        alertMsg(6,$resultCode);
        exit();
    }

//弹出消息框
function alertMsg($msg,$resultCode){
    header("Location:".$host."/emp/error.php?msg=".$msg."&code=".$resultCode);
    die;
}

//数据库类
class db {

    public $pdo;
    public static $PDOInstance;
    public $config;
    public $data;
    public $filed = '*';
    public $table;
    public $limit;
    public $order;
    public $where;
    public $left;

    const LOGIN = 7;
    const USER = 1;
    const GROUP = 2;
    const USERGROUP = 3;
    const LOG = 6;
    const WARING = 1;
    const ERROR = 2;
    const INFO = 0;

    public function __construct() {
        if (!self::$PDOInstance) {
                $this->config = json_decode(file_get_contents("/usr/local/emp/private/config/db.json"), true);

                $config = $this->config;
                $host = $config["data_base"]["db_host"];
                $dbname = $config["data_base"]["db_name"];
                $port = $config["data_base"]["db_port"];
                $username = $config["data_base"]["db_user"];
                $password = $config["data_base"]["db_pwd"];

                if ($config["data_base"]["db_host"] != 'localhost') {
                        $hosturl = "host=$host;";
                }
                try
                {
                        self::$PDOInstance = new PDO("pgsql:"
                                . $hosturl
                                . "port=$port;"
                                . "dbname=$dbname;"
                                , $username
                                , $password
                                , array(
                                        PDO::ATTR_PERSISTENT => true,
                                )
                        );
                } catch (Exception $ex) {
                        $path = $this->makelog("数据库初始化失败，已强制断开链接。<br />抓取到的异常栈如下：<br /><pre>" . print_r($ex, true) . "</pre>", 'db');

                        header("Content-type: text/html; charset=utf-8");
                        if ($config["SYSTEM"]["DEBUG"]) {
                                echo "数据库初始化失败，已强制断开链接。<br />详细信息请访问{$path}文件日志";
                        } else {
                                echo "数据库初始化失败，请联系系统管理员。";
                        }
                        die();
                }
                try {
                        self::$PDOInstance->query("SET client_encoding='UTF-8';");
                        self::$PDOInstance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                        self::$PDOInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                 } catch (Exception $exc) {
                    $this->pdo=NULL;
                    $doc = <<<DOC
                    <!DOCTYPE html>
                    <html>
                        <head>
                                <meta charset="UTF-8">
                                <script src="layer/jquery-1.11.1.min.js"></script>
                                <script src="layer/layer.js"></script>
                        <head>
                    <body>
DOC;

                print $doc;
                $info=L('服务器变更请刷新');
                print("<script>layer.msg('".$info."', {icon: 2,time: 30000},function(){location.reload();});</script>");
                print('</body></html>');
                exit();
                }
            }
        
            $this->pdo = self::$PDOInstance;
        }

    //写入日志方法
    public function log($content, $type = 0, $level = 0, $data = array()) {
        $time = date("Y-m-d H:i:s", time());
        $user = $_SESSION['eown']['em_id'];

        if (!empty($data)) {
            $this->makelog(implode('|', $data), 'error');
        }
        
        $table = 'T_EventLog_' . $_SESSION['eown']['em_ent_id'];
        $sql = 'INSERT INTO "' . $table . '" ("el_type","el_level","el_time","el_content","el_user") VALUES (:type,:level,:time,:content,:user);';

        $sth = $this->pdo->prepare($sql);

        if (strlen($content) > 1000) {
            $content = substr($content, 0, 800) . '（日志内容过长被截取）...';
        }

        $sth->bindValue(':type', $type);
        $sth->bindValue(':level', $level, PDO::PARAM_INT);
        $sth->bindValue(':time', $time, PDO::PARAM_INT);
        $sth->bindValue(':content', $content);
        $sth->bindValue(':user', $user);
        try
        {
            $sth->execute();
        } catch (Exception $ex) {
            $msg["msg"] = "日志记录无法记录，已中断程序" . $ex->getMessage();
            $msg["status"] = -1;
            echo json_encode($msg);
            exit();
        }
    }

    //日志数据处理及存储日志文件目录的生成
    public function makelog($msg, $prefix = "") {
        if ($prefix != "") {
            $prefix .= "_";
        }

        $dir = "/usr/local/emp/runtime/log/" . Date("Ym") . "/";
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $path = $dir . $prefix . date("Ymd") . ".log";
        $handle = fopen($path, "a");
        fwrite($handle, date("Y-m-d H:i:s", time()) . "\t" . $_SERVER["REMOTE_ADDR"] . "\t" . $msg . "\n");
        fclose($handle);
        return str_replace('../', '', $path);
    }
    
    //验证并保存session
    //验证登陆
    public function checkLogin($ecid) {
        $e_bss_number = $ecid;
        session_start();
        $session_id = session_id();
        $sql = 'SELECT * FROM "T_Enterprise" WHERE e_bss_number = :e_bss_number ';
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue(':e_bss_number', $e_bss_number, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result){
            //查询企业管理员的信息并存session
            $e_id=$result['e_id'];
            $sql2 = 'SELECT * FROM "T_EnterpriseManager" WHERE em_id = :e_id ';
            $sth2 = $this->pdo->prepare($sql2);
            $sth2->bindValue(':e_id', $e_id, PDO::PARAM_STR);
            $sth2->execute();
            $result2 = $sth2->fetch(PDO::FETCH_ASSOC);

            //存session
            $_SESSION['check'] = 'isoftstone';
            $_SESSION['em_id'] = $result2["em_id"];
            $result2['em_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];
            $result2['em_session_id']=$session_id;
            $_SESSION['em_lastlogin_ip'] = $result2['em_lastlogin_ip'];
            $_SESSION['eown'] = $result2;
            $_SESSION['eown']['em_session_id'] = $session_id;
            $_SESSION['eown']['em_lastlogin_time']=date("Y-m-d H:i:s",time());

            $_SESSION['ep'] = $result;
            $_SESSION['eown']['om_area'] = $result['e_area'];

            $data['em_lastlogin_time'] = date('Y-m-d H:i:s');
            $data['em_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];

            $sql_upd = 'UPDATE "T_EnterpriseManager"SET em_lastlogin_ip = :user_ip,em_lastlogin_time = :lastlogintime,em_session_id = :session_id WHERE em_id = :username';
            $sth = $this->pdo->prepare($sql_upd);
            $sth->bindValue(':username', $e_id, PDO::PARAM_STR);
            $sth->bindValue(':user_ip', $data['em_lastlogin_ip'], PDO::PARAM_STR);
            $sth->bindValue(':lastlogintime', $data['em_lastlogin_time'], PDO::PARAM_STR);
            $sth->bindValue(':session_id', $session_id, PDO::PARAM_STR); //$result2['em_session_id']
            $data = $sth->execute();
            $this->log('登录成功' . '。 IP：' . $_SERVER["REMOTE_ADDR"], 7);
            return 0;
        }else{
                return -1;
        }
    }
}
?>
