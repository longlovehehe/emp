<?php

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
                    $this->config = json_decode(file_get_contents("../private/config/db.json"), true);

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
                            $tools = new tools();
                            $path = $tools->log("数据库初始化失败，已强制断开链接。<br />抓取到的异常栈如下：<br /><pre>" . print_r($ex, true) . "</pre>", 'db');

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
                    //$this->smarty->assign('msg', L("帐号长时间未操作,请重新登录"));
                //$this->smarty->assign('href', "?m=login");
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
                //print("<script>parent.confirm('见到你真的很高兴', {icon: 6});location.href='?m=login';</script>");
                print("<script>layer.msg('".$info."', {icon: 2,time: 30000},function(){location.reload();});</script>");
                print('</body></html>');
                //$_SESSION['own']['em_lastlogin_time']=NULL;
               // $this->render('modules/system/login.tpl');

                exit();
                
        }

        }
        
        $this->pdo = self::$PDOInstance;
    }

	public function select() {
		$filed = $this->filed;
		$table = $this->table;
		$where = $this->where;
		$left = $this->left;
		$order = $this->order;
		$limit = $this->limit;
		$sql = <<<SQL
SELECT
                {$filed}
FROM
                "{$table}"
                {$left}
                 {$where}
                {$order}
                {$limit}
SQL;
		$query = $this->pdo->query($sql);
		return $query->fetchAll();
	}

	public function saveV2() {

	}

	public function left($left = '') {
		if ($left == '') {
			$this->left = '';
		} else {
			$this->left .= ' ' . $left . ' ';
		}
		return $this;
	}

	public function table($table) {
		$this->table = $table;
		return $this;
	}

	public function filed($filed, $iskey = TRUE) {
		if ($iskey) {
			$this->filed = implode(',', array_keys($filed));
		} else {
			$this->filed = implode(',', $filed);
		}
		return $this;
	}

	// 增加条件 $this->where(' 1 = 1');
	// 清除条件增加新条件 $this->where()->where('1=1');
	public function where($where = '') {
		if ($this->where != '' && $where != '') {
			$this->where .= ' AND  ' . $where;
			return $this;
		}
		if ($where == '') {
			$this->where = '';
		} else {
			$this->where = ' WHERE ' . $where;
		}
		return $this;
	}

	public function limit($start, $num) {
		$this->limit = "LIMIT {$start} OFFSET {$num}";
		return $this;
	}

	public function order($order = '') {
		if ($order == '') {
			$this->order = '';
		} else {
			$this->order = "ORDER BY {$order}";
		}
		return $this;
	}

	public function limitstr($limit) {
		$this->limit = $limit;
		return $this;
	}

	public function total($sql) {
		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();
		return $result['total'];
	}

	public function quotes($arr) {
		if (!is_array($arr)) {
			return "'$arr'";
		} else {
			$tmp = array();
			foreach ($arr as $value) {
				$tmp[] = "'$value'";
			}
			return $tmp;
		}
	}

	public function getpdo() {
		return $this->pdo;
	}

	public function msg($msgtext, $status = 0) {
		$msg["msg"] = $msgtext;
		$msg["status"] = $status;
		return $msg;
	}

	public function msg1($msgtext, $status = 0, $arr) {
		$msg["msg"] = $msgtext;
		$msg["status"] = $status;
		$msg["arr"] = $arr;
		return $msg;
	}

	public function md5r() {
		return strtoupper(md5(uniqid(rand(), true)));
	}

	//写入日志方法
	public function log($content, $type = 0, $level = 0, $data = array()) {
		$time = date("Y-m-d H:i:s", time());
		$user = $_SESSION['eown']['em_id'];

		if (!empty($data)) {
			$tools = new tools();
			$tools->log(implode('|', $data), 'error');
		}
		if ($_SESSION['eown']['em_ent_id'] == '') {
			//throw new Exception('e_id 为 null');
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

	public function eventlog($ex, $str) {
		$event['id'] = $this->md5r();
		if ($ex->getCode() == "23505") {
			$log['msg'] = $str . '。' . DL('原因') . '：' . DL('重复');
		} else {
			$log['msg'] = $str . '。' . DL('事件ID') . ' ' . $event['id'];
		}

		$event['msg'] = $ex->getMessage();
		$log['event'] = $event;
		return $log;
	}

}
