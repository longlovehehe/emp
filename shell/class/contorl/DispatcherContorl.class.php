<?php

/**
 * 主控制分发器
 * @package EMP
 * @require {@see contorl} {@see system}
 */
class DispatcherContorl extends contorl {

	public function __construct() {
		parent::__construct();
	}

	public function lang() {
		$lang = json_encode(coms::$res);
		coms::head('script');
		print("window.lang = $lang ;");
	}

    /**
     * 分发器公用方法
     * @param String $flag 值：none login admin
     */
    public function common($flag = 'none') {

            $tools = new tools();
            if (file_exists('../private/config/db.json')) {
                    $tools->init();
            } else {
                    $this->smarty->template_dir = "../template";
                    $this->smarty->cache_dir = "../runtime/cache";
                    $this->smarty->compile_dir = "../runtime/template_c";
                    $init = new InitContorl();

                    if (isset($_REQUEST['shell'])) {
                            $init->initShell();
                    } else {
                            $init->init_lang();
                    }
                    exit();
            }

            switch ($flag) {
                    case 'none':
                            break;
                    case 'login':
                            $tools->safe360();
                            $this->checklogin();
                            $this->otherLogin();
                            $this->permissions($_SESSION['eown']);
                            $this->check_timeout($_SESSION['eown']['em_lastlogin_time']);
                            $system = new system(array("username" => $_SESSION['eown']['em_id'], "password" => $_SESSION['eown']['em_pswd']));
                            $result = $system->checkLogin_change();

                            if ($result == -2) {
                                    // $this->smarty->assign ( 'msg' , "密码错误" );
                                    // $this->smarty->assign ( 'href' , "?m=login" );
                                    // $this->htmlrender ( "viewer/href.tpl" );
                                    $this->smarty->assign('msg', L("密码不正确 请联系管理员解决"));
                                    $this->htmlrender('modules/system/login.tpl');
                                    exit();
                            }
                            $data['e_id'] = $_SESSION['eown']['em_ent_id'];
                            $ep = new enterprise($data);
                            $_SESSION['ep'] = $ep->getByid();

                            break;
                    case 'admin':
                            $tools->safe360();
                            $this->checklogin();
                            $this->otherLogin();
                            $this->permissions($_SESSION['eown'], true);
                            break;
            }
    }
    public function ajaxcheck_out(){
            $this->ajaxcheck_timeout($_SESSION['eown']['em_lastlogin_time']);
    }
 	public function ajaxcheck_timeout($session_time){
	  		session_cache_expire(20);
            $lifetime=session_cache_expire()*60;//转化为秒
            $old_time=strtotime ($session_time);
            if(time()-$old_time>$lifetime){
				echo -1;
            }else{
                $_SESSION['eown']['em_lastlogin_time']=date("Y-m-d H:i:s",time());
				echo 1;
            }
    }
    public function checklogin() {
            $system = new system();
            $res=$system->check();
            if($res['status']==TRUE){
                 $this->smarty->assign('msg', L($res['msg']));
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
                $info=L($res['msg']);
                //print("<script>parent.confirm('见到你真的很高兴', {icon: 6});location.href='?m=login';</script>");
                print("<script>layer.alert('".$info."', {icon: 2},function(){location.href='?m=login';});</script>");
                print('</body></html>');
                //$_SESSION['own']['em_lastlogin_time']=NULL;
               // $this->render('modules/system/login.tpl');
                exit();
            }
        }

	public function otherLogin() {
		session_cache_expire(20);
		session_start();
		$system = new system();
                 //var_dump($_SESSION['eown']);die;
		$otherlogininfo = $system->checkOtherLogin($_SESSION['eown']);
		if ($otherlogininfo['status']) {
			header('HTTP/1.1 401 Unauthorized');
			$msg = L('该账户已在其他地方登录');
			$_SESSION['eown'] = NULL;
			$this->smarty->assign('msg', $msg);
			$this->htmlrender('modules/system/login.tpl');
			exit();
		}
	}

	public function loader() {
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		require_once '../shell/class/contorl/LoaderContorl.class.php';
		$loaderContorl = new LoaderContorl();
		if (method_exists($loaderContorl, $action)) {
			$loaderContorl->$action();
		}
	}

	public function help() {
		$this->htmlrender('_help.tpl');
	}

	public function api() {
		require_once $this->tools->getModule('api/index');
	}

	public function nonsupport() {
		//$this->htmlrender ( 'viewer/nonsupport.tpl' );
	}

	public function login() {
		$this->smarty->assign('title', '集群通 - 登录');
		$this->smarty->display('modules/system/login.tpl');
	}

	/**
	 * 配置页
	 */
	public function config() {
		$tools = new tools();
		$tools->setlangconfig($_REQUEST['lang']);
		$this->smarty->assign('title', '初始化');
		$this->smarty->assign('lang', $_REQUEST['lang']);
		$this->htmlrender('_init.tpl');
	}

	public function get_sessionid(){
            $username=$_SESSION['eown']['em_id'];
            $usermd5= md5($_SESSION['eown']['em_id'].$_SESSION['eown']['em_pswd']);
            $session_id= session_id();
            echo json_encode(array("username"=>$username,"md5"=>$usermd5,"session_id"=>$session_id,"type"=>"emp"));
        }

	public function logout() {
		$check = $_SESSION['check'];
		session_cache_expire(20);
		session_start();
		if ($_SESSION['eown'] == NULL) {
			if($check != 'isoftstone'){
				$this->htmlrender("modules/system/login.tpl");
				exit();
			}else{
				$_SESSION['check'] = NULL;
				echo '<script>window.close();</script>';
				exit();
			}
			
		} else {
			$db = new db();
			$db->log(DL('注销成功') . '。 IP：' . $_SERVER["REMOTE_ADDR"], 7, 0);

			$_SESSION['eown'] = NULL;
			if($check != 'isoftstone'){
				$this->smarty->assign('msg', L("注销成功"));
				$this->smarty->assign('href', "?m=login");
				$this->htmlrender('viewer/href.tpl');
			}else{
				$_SESSION['check'] = NULL;
				echo '<script>window.close();</script>';
				exit();
			}
		}
	}
        /**
         * 登陆超时检查
         */
        public function check_timeout($session_time){
            $lifetime=session_cache_expire()*60;//转化为秒
            $old_time=strtotime ($session_time);
            if(time()-$old_time>$lifetime){
                 $this->smarty->assign('msg', L("帐号长时间未操作,请重新登录"));
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
                $info=L('帐号长时间未操作,请重新登录');
                //print("<script>parent.confirm('见到你真的很高兴', {icon: 6});location.href='?m=login';</script>");
                print("<script>layer.alert('".$info."', {icon: 2},function(){location.href='?m=login';});</script>");
                print('</body></html>');
                //$_SESSION['own']['em_lastlogin_time']=NULL;
               // $this->render('modules/system/login.tpl');
                exit();
            }else{
                $_SESSION['eown']['em_lastlogin_time']=date("Y-m-d H:i:s",time());
            }
        }

	public function login_check() {
		$this->common();
		session_cache_expire(20);
		session_start();
		$system = new system($_REQUEST);
		$data = $system->checkLogin();
		if ($data == -1) {
			$this->smarty->assign('msg', L("帐号错误"));
			$this->smarty->assign('href', "?m=login");
			$this->htmlrender("viewer/href.tpl");
			exit();
		}
		if ($data == -2) {
			$this->smarty->assign('msg', L("密码错误"));
			$this->smarty->assign('href', "?m=login");
			$this->htmlrender("viewer/href.tpl");
			exit();
		}
		if ($data == 0) {
			$this->smarty->assign('msg', L("登陆成功"));
			$this->smarty->assign('href', "?m=enterprise&a=view");
			$this->htmlrender("viewer/href.tpl");
			exit();
		}
	}

	public function system() {
		$this->common('login');
		require_once '../shell/class/dao/system.class.php';
		require_once '../shell/class/contorl/SystemContorl.class.php';

		$smarty = $this->smarty;
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		$SystemContorl = new SystemContorl($smarty, $tools);
		if (method_exists($SystemContorl, $action)) {
			$SystemContorl->$action();
		}
	}

	public function announcement() {
		die;
		$this->common('login');
		$smarty = $this->smarty;
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		require_once "../shell/class/page.class.php";
		require_once '../shell/class/dao/announcement.class.php';
		require_once '../shell/class/contorl/AnnouncementContorl.class.php';

		$AnnouncementContorl = new AnnouncementContorl($smarty, $tools);
		if (method_exists($AnnouncementContorl, $action)) {
			$AnnouncementContorl->$action();
		}
	}

	public function enterprise() {

		$this->common('login');
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		require_once '../shell/class/contorl/EnterpriseViewContorl.class.php';
		require_once '../shell/class/contorl/EnterpriseAdminContorl.class.php';
		require_once '../shell/class/contorl/EnterpriseUsersContorl.class.php';
		require_once '../shell/class/contorl/EnterpriseGroupsContorl.class.php';
		require_once '../shell/class/contorl/EnterpriseUserGroupContorl.class.php';
		require_once '../shell/class/contorl/EnterpriseExportContorl.class.php';

		$enterpriseViewContorl = new EnterpriseViewContorl();
		$enterpriseAdminContorl = new EnterpriseAdminContorl();
		$enterpriseUsersContorl = new EnterpriseUsersContorl();
		$enterpriseGroupsContorl = new EnterpriseGroupsContorl();
		$enterpriseUserGroupContorl = new EnterpriseUserGroupContorl();
		$enterpriseExportContorl = new EnterpriseExportContorl();
		$enterpriseLogContorl = new EnterpriseLogContorl();

		if (method_exists($enterpriseViewContorl, $action)) {
			$enterpriseViewContorl->$action();
		}

		if (method_exists($enterpriseUsersContorl, $action)) {
			$enterpriseUsersContorl->$action();
		}

		if (method_exists($enterpriseGroupsContorl, $action)) {
			$enterpriseGroupsContorl->$action();
		}
		if (method_exists($enterpriseExportContorl, $action)) {
			$enterpriseExportContorl->$action();
		}
		if (method_exists($enterpriseUserGroupContorl, $action)) {
			$enterpriseUserGroupContorl->$action();
		}
		if (method_exists($enterpriseLogContorl, $action)) {
			$enterpriseLogContorl->$action();
		}
	}

	public function device() {
		$this->common('login');

		$smarty = $this->smarty;
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		$deviceContorl = new DeviceContorl($smarty, $tools);
		if (method_exists($deviceContorl, $action)) {
			try
			{
				$deviceContorl->$action();
			} catch (Exception $ex) {
				$tools->log('发送了' . $name . '消息。命令：' . $ex->getMessage() . "。结果：" . $ex->getCode(), 'shell_error');
				$tools->call($ex->getMessage(), $ex->getCode(), true);
			}
		}
	}
 /**
	 * 设备模块分发
	 */
	public function terminal() {
		$this->common('login');

		$smarty = $this->smarty;
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		require_once '../shell/class/dao/terminal.class.php';
		require_once '../shell/class/page.class.php';
		require_once '../shell/class/contorl/TerminalContorl.class.php';
                
		$terminalContorl = new TerminalContorl($smarty,$tools);
		
		if (method_exists($terminalContorl, $action)) {
			$terminalContorl->$action();
		}

	}
	public function manager() {
		die;
	}

	public function area() {
		$this->common('login');
		$smarty = $this->smarty;
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		require_once '../shell/class/dao/log.class.php';
		require_once '../shell/class/dao/area.class.php';
		require_once "../shell/class/page.class.php";
		require_once '../shell/class/dao/enterprise.class.php';
		require_once '../shell/class/dao/admins.class.php';
		require_once '../shell/class/dao/groups.class.php';

		require_once '../shell/class/contorl/AreaContorl.class.php';

		$AreaContorl = new AreaContorl($smarty, $tools);

		if (method_exists($AreaContorl, $action)) {
			$AreaContorl->$action();
		}
	}

	public function product() {
		$this->common('login');
		$smarty = $this->smarty;
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		require_once '../shell/class/dao/log.class.php';
		require_once '../shell/class/dao/area.class.php';
		require_once "../shell/class/page.class.php";
		require_once '../shell/class/dao/enterprise.class.php';
		require_once '../shell/class/dao/admins.class.php';
		require_once '../shell/class/dao/groups.class.php';
		require_once '../shell/class/dao/product.class.php';

		require_once '../shell/class/contorl/ProductContorl.class.php';

		$ProductContorl = new ProductContorl($smarty, $tools);

		if (method_exists($ProductContorl, $action)) {
			$ProductContorl->$action();
		}
	}

	public function log() {
		$this->common('login');
		$smarty = $this->smarty;
		$tools = $this->tools;
		$action = $tools->get('action') != '' ? $tools->get('action') : $tools->get('a');

		require_once '../shell/class/dao/log.class.php';
		require_once '../shell/class/dao/area.class.php';
		require_once "../shell/class/page.class.php";
		require_once '../shell/class/dao/enterprise.class.php';
		require_once '../shell/class/dao/admins.class.php';
		require_once '../shell/class/dao/groups.class.php';

		require_once '../shell/class/contorl/LogContorl.class.php';

		$LogContorl = new LogContorl($smarty, $tools);

		if (method_exists($LogContorl, $action)) {
			$LogContorl->$action();
		}
	}

	public function test() {
		print_r($_COOKIE);
	}

}
