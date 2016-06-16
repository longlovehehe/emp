<?php
/**
 * 企业用户控制器
 * @package 企业管理
 * @subpackage 控制器层
 * @require {@see contorl} {@see page} {@see enterprise} {@see users}
 */
class EnterpriseUsersContorl extends contorl {

	public $enterprise;
	public $user;
	public $tools;
	public $groups;
	public  $page;

	public function __construct() {
		parent::__construct();
		$this->enterprise = new enterprise($_REQUEST);
		$this->tools = new tools();
		$this->groups = new groups($_REQUEST);
		$this->gprs = new gprs($_REQUEST);
		$this->pttmember = new pttmember($_REQUEST);
	        //列表页分条数显示
	        if($_REQUEST['user_num']){
	            $_SESSION['user_page_num'] = $_REQUEST['user_num'];
	        }
	        if($_SESSION['user_page_num']){
	            $_REQUEST['num'] = $_SESSION['user_page_num'];
            
	        }
	        $this->user = new users($_REQUEST);
	        $this->page = new page($_REQUEST);
	}

	public function shelluser() {
		//die;
		$result = $this->user->shelluser();
		$this->smarty->assign("list", $result);
		$this->htmlrender('modules/enterprise/shelluser.tpl');
	}

	public function all_user_item() {
		die;
		if ($_REQUEST['u_number'] == '') {
			return NULL;
		}
		$alllist['list'] = $this->user->getUserList();

		foreach ($alllist['list'] as $key => $value) {
			$data['e_id'] = $value['u_e_id'];
			$this->enterprise->set($data);
			$data = $this->enterprise->getByid();
			$alllist['list'][$key]['ep'] = $data;
			//$this->smarty->assign('ep', $data);
		}
		$this->smarty->assign('list', $alllist['list']);
		$this->htmlrender('modules/enterprise/allusers_item.tpl');
	}

	public function allusers() {
		die;
		$this->render('modules/enterprise/allusers.tpl', '用户搜索');
	}

    public function saveUserVerify($num = 1) {
            $edit = FALSE;
            if ($_REQUEST['do'] == 'edit') {
                    $edit = TRUE;
            }
            $item_e = $this->enterprise->getByid();
            $usernum = $this->user->getTotal(FALSE);
            $info = $this->user->getById();
            $phone_num = $this->user->getusertotal(1);
            $dispatch_num = $this->user->getusertotal(2);
            $gvs_num = $this->user->getusertotal(3);
            $flag = $item_e['e_mds_users'] - ($usernum + $num);
            if ($_REQUEST['do'] == 'edit') {
                    $edit = TRUE;
                    if ($_REQUEST['u_sub_type'] == 1 && $info['u_sub_type'] != 1) {
                            if ($item_e['e_mds_phone'] - ($phone_num + $num) <0) {
                                    throw new Exception(L('手机用户数超过该企业手机用户总数'), 0);
                            }
                    }
                    if ($_REQUEST['u_sub_type'] == 2 && $info['u_sub_type'] != 2) {
                            if ($item_e['e_mds_dispatch'] - ($dispatch_num + $num) <0) {
                                    throw new Exception(L('调度台用户数超过该企业调度台用户总数'), 0);
                            }
                    }
                    if ($_REQUEST['u_sub_type'] == 3 && $info['u_sub_type'] != 3) {
                            if ($item_e['e_mds_gvs'] - ($gvs_num + $num) < 0) {
                                    throw new Exception(L('GVS用户数超过该企业GVS用户总数'), 0);
                            }
                    }
                } else {
                    if ($_REQUEST['u_sub_type'] == 1) {
                            if ($item_e['e_mds_phone'] - ($phone_num + $num) < 0) {
                                    throw new Exception(L('手机用户数超过该企业手机用户总数'), 0);
                            }
                    }
                    if ($_REQUEST['u_sub_type'] == 2) {
                            if ($item_e['e_mds_dispatch'] - ($dispatch_num + $num) < 0) {
                                    throw new Exception(L('调度台用户数超过该企业调度台用户总数'), 0);
                            }
                    }
                    if ($_REQUEST['u_sub_type'] == 3) {
                            if ($item_e['e_mds_gvs'] - ($gvs_num + $num) < 0) {
                                    throw new Exception(L('GVS用户数超过该企业GVS用户总数'), 0);
                            }
                    }
                }
            if (!$edit) {
                if ($flag < 0) {
                        throw new Exception(L('企业用户数超过GQT-Server用户数'), -1);
                }
            }
    }

	function users_face_item() {
		$pic = new pic($_REQUEST);
		$pic->show();
	}

	function users_face() {
		$pic = new pic($_REQUEST);
		try
		{
			$result['msg'] = $pic->getId();
			$result['status'] = 0;
		} catch (Exception $ex) {
			$result['msg'] = $ex->getMessage();
			$result['status'] = -1;
		}
		$result = json_encode($result);
		print <<<RESULT
                <script>parent.callback($result);</script>
RESULT;
	}

    function users() {
            $data = $this->enterprise->getByid();
	//列表页分条数 选中的显示相应颜色
        if($_REQUEST['num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_REQUEST['num']] = 'style="background:#E5E5E5"';
        }elseif($_SESSION['user_page_num']){
            unset($_SESSION['color']);
            $_SESSION['color'][$_SESSION['user_page_num']] = 'style="background:#E5E5E5"';
        }else{
            unset($_SESSION['color']);
            $_SESSION['color'][10] = 'style="background:#E5E5E5"';
        }
            $this->smarty->assign('data', $data);
            $this->smarty->assign('ep', $data);
            $this->smarty->assign('page', $_REQUEST['page']);
            if($_SESSION['ident']=='VT'){
                $this->render('modules/enterprise/users_vt.tpl', L('企业用户'));
            }else if($_SESSION['ident']=='GQT'){
                $this->render('modules/enterprise/users.tpl', L('企业用户'));
            }else{
                $this->render('modules/enterprise/users_vt.tpl', L('企业用户'));
            }
    }

function users_save() {

        if ($_REQUEST['do'] == 'edit') {
                $item = $this->user->getById($_REQUEST);
                $this->smarty->assign('item', $item);
                $this->smarty->assign('data', $_REQUEST);
                $this->smarty->assign('page', $_REQUEST['page']);
                 if($_SESSION['ident']=='VT'){
                    $this->render('modules/enterprise/users_save_vt.tpl', L('编辑企业用户'));
                 }else if($_SESSION['ident']=='GQT'){
                     $this->render('modules/enterprise/users_save.tpl', L('编辑企业用户'));
                 }else{
                     $this->render('modules/enterprise/users_save_vt.tpl', L('编辑企业用户'));
                 }
        } else {
                die;
                $this->smarty->assign('data', $_REQUEST);
                $this->render('modules/enterprise/users_save.tpl', '新增企业用户');
        }
}

	function users_auto_save() {
		die;
		$this->smarty->assign('data', $_REQUEST);
		$this->render('modules/enterprise/users_auto_save.tpl', '批量新增企业用户');
	}

    function users_item() {
            $item_e = $this->enterprise->getByid();
            $this->page->setTotal($this->user->getTotal());
            $list = $this->user->getList($this->page->getLimit());
            $maxpage = $this->page->getPages();
            foreach ($list as $val) {
                    $pg_list[$val['u_number']] = $this->groups->getuserPgname($val['u_number'],$val['u_default_pg']);
            }
            $numinfo = $this->page->getNumInfo();
            $prev = $this->page->getPrev();
            $next = $this->page->getNext();
            $this->smarty->assign('list', $list);
            $this->smarty->assign('numinfo', $numinfo);
            $this->smarty->assign('pg_list', $pg_list);
            $this->smarty->assign('prev', $prev);
            $this->smarty->assign('next', $next);
            $this->smarty->assign('maxpage', $maxpage);
            $this->smarty->assign('data', $_REQUEST);
            $this->smarty->assign('item_e', $item_e);
            $this->smarty->assign('page', $_REQUEST['page']);
            if ($_REQUEST['type'] == 'append') {
                    $this->htmlrender('modules/enterprise/users_item.append.tpl');
            } else {
                    if($_SESSION['ident']=='VT'){
                        $this->htmlrender('modules/enterprise/users_item_vt.tpl');
                    }else if($_SESSION['ident']=='GQT'){
                        $this->htmlrender('modules/enterprise/users_item.tpl');
                    }else{
                        $this->htmlrender('modules/enterprise/users_item_vt.tpl');
                    }
            }
    }

	function users_del() {
		die;
		$this->enterprise->changeSync(true, 28);
		$list = $this->tools->get('list');
		$result[count] = $this->user->delList($list);
		echo $result[count];
		exit();
	}

	function users_save_shell() {
		try
		{
			$this->saveUserVerify();
			$pginfo = $this->user->getPGinfo($_REQUEST['u_default_pg']);
			$data = $this->user->get();
			$data['pg_name'] = $pginfo[0]['pg_name'];
			$this->user->set($data);
			$msg = $this->user->save();
		} catch (Exception $ex) {
			//$this->user->log($ex->getMessage(), 1, 2);
			$this->tools->call($ex->getMessage(), -1, TRUE);
		}

		echo json_encode($msg);
		$this->enterprise->changeSync(true, 28);
	}

	function users_auto_save_shell() {
		die;
		try
		{
			$this->saveUserVerify($_REQUEST['u_auto_number']);
		} catch (Exception $ex) {
			$goto = '?m=enterprise&a=users&e_id=' . $_REQUEST['e_id'];
			print('<script>parent.notice("' . $ex->getMessage() . '","' . $goto . '");</script>');
			exit();
		}

		print str_repeat(" ", 4096);
		$this->user->createUsers();
		$this->enterprise->changeSync(true, 28);
	}

	function users_batch() {
		if (!empty($_REQUEST['checkbox'])) {
			$this->enterprise->changeSync(true, 28);
		}
		foreach ($_REQUEST['checkbox'] as $value) {
			$data['e_id'] = $_REQUEST['e_id'];
			$data['u_ug_id'] = $_REQUEST['u_ug_id'];
			$data['u_number'] = $value;

			$data['u_product_id'] = $_REQUEST['u_product_id'];
			$data['u_gis_mode'] = $_REQUEST['u_gis_mode'];
            $data['u_mms_default_rec_num'] = $_REQUEST['u_mms_default_rec_num'];
            $data['u_alarm_inform_svp_num'] = $_REQUEST['u_alarm_inform_svp_num'];
            $data['u_only_show_my_grp'] =  $_REQUEST['u_only_show_my_grp'];
			$this->user->set($data);
			$this->user->batchUser();
		}
	}

	function users_batch_ug() {
		if (!empty($_REQUEST['checkbox'])) {
			$this->enterprise->changeSync(true, 28);
		}
		$users = new users($_REQUEST);
		$usergroup = new usergroup($_REQUEST);
		$ug_name = $usergroup->getselectinfo($_REQUEST['u_ug_id']);
		$ug_name = $ug_name[0]['ug_name'];
		foreach ($_REQUEST['checkbox'] as $value) {
			$data['e_id'] = $_REQUEST['e_id'];
			$data['u_ug_id'] = $_REQUEST['u_ug_id'];
			$data['u_number'] = $value;
			$data['ug_name'] = $ug_name;
			$user_name = $users->hasUser($value);

			$data['u_name'] = $user_name['u_name'];
			$this->user->set($data);
			$this->user->batchUser_ug();
		}
	}

	function users_move() {
		die;
		try
		{
			if (!empty($_REQUEST['checkbox'])) {
				//当前企业状态需要同步
				$this->enterprise->changeSync(true, 28);
				//接收用户企业状态需要同步
				$toe = new enterprise(array('e_id' => $_REQUEST['to_e_id']));
				$toe->changeSync(true, 28);
				foreach ($_REQUEST['checkbox'] as $value) {
					$data['e_id'] = $_REQUEST['e_id'];
					$data['to_e_id'] = $_REQUEST['to_e_id'];
					$data['u_number'] = $value;

					$this->user->set($data);
					$this->user->moveUsers();
				}
			}
		} catch (Exception $ex) {
			if ($ex->getCode() == 23505) {
				$this->tools->call(L('目标企业已经存在该用户号码同名的用户，无法再移入'), -1, true);
			}
			$log = DL("移动用户到其它企业出现失败") . "：" . $ex->getMessage();
			$this->user->log($log, db::USER, db::ERROR);
			$this->tools->call($log, -1, true);
		}
		$this->tools->call(L('操作完成'), 0, true);
	}

	function users_item_v2() {
		$item_e = $this->enterprise->getByid();
		$total = $this->user->getTotal();

		$this->page->setTotal($total);
		$list = $this->user->getListV2();
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('data', $_REQUEST);
		$this->smarty->assign('item_e', $item_e);
		$this->smarty->assign('total', $total);
		if ($_REQUEST['type'] == 'append') {
			if (count($list) != 0) {
				$html = $this->htmlrender('modules/enterprise/users_item.append.tpl', true);
			} else {
				$html = "";
			}
			echo $html;
		} else {
			$this->htmlrender('modules/enterprise/users_item.tpl');
		}
	}

	/**
	 *获取手机号是否存在？
	 */
	function getmob() {
		$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_mobile_phone'] != $_REQUEST['u_mobile_phone'] && $_REQUEST['u_mobile_phone'] != "") {
			$res = $this->user->getmobile($_REQUEST['u_mobile_phone']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}
	}
	/**
	 *获取u_udid是否存在？
	 */
	function getudid() {
		$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_udid'] != $_REQUEST['u_udid'] && $_REQUEST['u_udid'] != "") {
			$res = $this->user->getudid($_REQUEST['u_udid']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}
	}
	/**
	 *获取u_imsi是否存在？
	 */
	function getimsi() {
		$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_imsi'] != $_REQUEST['u_imsi'] && $_REQUEST['u_imsi'] != "") {
			$res = $this->user->getimsi($_REQUEST['u_imsi']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}
	}
/**
        *获取u_imei是否存在？
        */
        function getimei() {
                $user = new users(array("u_number" => $_REQUEST['u_number']));
                //去库里查询imei信息
                $term = new terminal($_REQUEST);
                $termInfo = $term->checkexcel_imei($_REQUEST['u_imei']);

                $flag = $user->getById();
                if ($flag['u_imei'] != $_REQUEST['u_imei'] && $_REQUEST['u_imei'] != "") {
                    $back = $this->getAjaxreturn($_REQUEST['u_imei'],$_REQUEST['u_bind_phone'],$_REQUEST['u_terminal_type'],$_REQUEST['e_id']);
                    echo json_encode(array('status'=>$back,'res'=>$termInfo));
                }else if($_REQUEST['u_imei'] == ""){
                    echo json_encode(array('status'=>'isnull','res'=>$termInfo));
                }else if($flag['u_imei']==$_REQUEST['u_imei']){
                    if($flag['u_terminal_type']==$_REQUEST['u_terminal_type']){
                        echo json_encode(array('status'=>'issame','res'=>$termInfo));
                    }else{
                        $back = $this->getAjaxreturn($_REQUEST['u_imei'],$_REQUEST['u_bind_phone'],$_REQUEST['u_terminal_type'],$_REQUEST['e_id']);
                        echo json_encode(array('status'=>$back,'res'=>$termInfo));
                    }
                }
        }
        public function getAjaxreturn($u_imei,$u_bind_phone,$u_terminal_type,$e_id){
            $res = $this->user->getimei($_REQUEST['u_imei']);//不在用户中也不再终端中
                    if ($res == false) {
                        if($_REQUEST['u_bind_phone']=="0"){
                             if($_REQUEST['u_terminal_type']==""){
                                return "1";//不在库中可以正常保存
                            }else{
                                return "8";//非其他选项 必须是库里的
                            }
                        }else{
                                return "8";//必须是库里的
                        }
                    }else{
                        $info=check_md_imei($_REQUEST['u_imei'],$_REQUEST['e_id']);
                        if(is_array($info)){
                            if($info['res']===TRUE){
                               if($info['md_type']==$_REQUEST['u_terminal_type']){
                                   return "5";//需要绑定
                               }else{
                                   return "7";//在库中 符合所属 但类型不同
                               }
                           }
                        }else if($info==="Binding"){
                        return "3";//已被绑定 不能保存
                        }else if($info==="Not Belong"){
                        return "4";//在库中但不属于所属代理商,不能保存
                        }else if($info==="isnull"){
                        return "6";//传过来的imei为空 可以保存
                        }else if($info==="Not in the library"){
                        return "2";//不在库中可以正常保存
                        }else{
                        return "2";//已存在IMEI
                        }
                    }
        }

    /**
    *获取u_meid是否存在？
    */
    function getmeid() {
        $user = new users(array("u_number" => $_REQUEST['u_number']));
        //去库里查询imei信息
        $term = new terminal($_REQUEST);
        $termInfo = $term->checkexcel_meid($_REQUEST['u_meid']);
        $flag = $user->getById();
        if ($flag['u_meid'] != $_REQUEST['u_meid'] && $_REQUEST['u_meid'] != "") {
            $back = $this->getAjaxreturn_meid($_REQUEST['u_meid'],$_REQUEST['u_bind_phone'],$_REQUEST['u_terminal_type'],$_REQUEST['e_id']);

            echo json_encode(array('status'=>$back,'res'=>$termInfo));

        }else if($_REQUEST['u_meid'] == ""){

            echo json_encode(array('status'=>'isnull','res'=>$termInfo));

        }else if($flag['u_meid']==$_REQUEST['u_meid']){

            if($flag['u_terminal_type']==$_REQUEST['u_terminal_type']){

                echo json_encode(array('status'=>'issame','res'=>$termInfo));

            }else{

                $back = $this->getAjaxreturn_meid($_REQUEST['u_meid'],$_REQUEST['u_bind_phone'],$_REQUEST['u_terminal_type'],$_REQUEST['e_id']);

                echo json_encode(array('status'=>$back,'res'=>$termInfo));

            }

        }
    }

    public function getAjaxreturn_meid($u_meid,$u_bind_phone,$u_terminal_type,$e_id)
    {
        $res = $this->user->getmeid($_REQUEST['u_meid']);//不在用户中也不再终端中
        if ($res == false) {
            if($_REQUEST['u_bind_phone']=="0"){
                if($_REQUEST['u_terminal_type']==""){
                    return "1";//不在库中可以正常保存
                }else{
                    return "8";//非其他选项 必须是库里的
                }
            }else{
                return "8";//必须是库里的
            }
        }else{
            $info=check_md_meid($_REQUEST['u_meid'],$_REQUEST['e_id']);
            if(is_array($info)){
                if($info['res']===TRUE){
                   if($info['md_type']==$_REQUEST['u_terminal_type']){
                       return "5";//需要绑定
                   }else{
                       return "7";//在库中 符合所属 但类型不同
                   }
               }
            }else if($info==="Binding"){
                return "3";//已被绑定 不能保存
            }else if($info==="Not Belong"){
                return "4";//在库中但不属于所属代理商,不能保存
            }else if($info==="isnull"){
                return "6";//传过来的imei为空 可以保存
            }else if($info==="Not in the library"){
                return "2";//不在库中可以正常保存
            }else{
                return "2";//已存在MEID
            }
        }
    }

/**
	 *获取u_iccid是否存在？
	 */
	function geticcid() {
		/*$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_iccid'] != $_REQUEST['u_iccid'] && $_REQUEST['u_iccid'] != "") {
			$res = $this->user->geticcid($_REQUEST['u_iccid']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}*/
		$this->enterprise->set($_REQUEST);
        if($_REQUEST['type']=='imsi'){
            $res =  $this->gprs->check_u_imsi();
        }elseif($_REQUEST['type']=='number'){
            $res =  $this->gprs->check_u_mobile();
        }else{
            $res =  $this->gprs->check_iccid();
		}
        echo json_encode($res);
	}
/**
	 *获取u_mac是否存在？
	 */
	function getmac() {
		$user = new users(array("u_number" => $_REQUEST['u_number']));
		$flag = $user->getById();
		if ($flag['u_mac'] != $_REQUEST['u_mac'] && $_REQUEST['u_mac'] != "") {
			$res = $this->user->getmac($_REQUEST['u_mac']);

			if ($res == false) {
				echo "1";
			} else if (count($res) >= 1) {
				echo "2";
			}
		}
	}
         /**
         * 判断该用户号码是否为该企业用户
         */
        function check_number(){
            $res=$this->user->getById();
            if($res){
                echo  "1";
            }else{
                echo "2";
            }
        }
        public function get_random_passwd(){
            /*$passwd = str_replace("0", "2", uniqid());
            $passwd = str_replace("1", "2", $passwd);
            $passwd = str_replace("l", "m", $passwd);
            $passwd = str_replace("o", "p", $passwd);
            $passwd = str_replace("O", "p", $passwd);*/
            $passwd = $this->user->random_str(8);
            echo $passwd;
        }

}
