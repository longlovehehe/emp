<?php

class EnterpriseViewContorl extends contorl {

    public function __construct() {
            parent::__construct();
            $this->page = new page($_REQUEST);
    }

    function sync() {
            $enterprise = new enterprise($_REQUEST);
            $data = $enterprise->getByid();
            $data_item = $enterprise->get();
            try
            {
                    if ($data["e_has_vcr"] == 0) {
                            $this->tools->send("ExSync", $data["e_id"] . " " . $data['e_sync']);
                    } else {
                            $this->tools->send("ExSyncVCR", $data["e_id"] . " " . $data['e_sync']);
                    }
            } catch (Exception $ex) {
                    $enterprise->updateStatus(array(3, $data_item['e_id']));
                    $this->log(DL("同步数据失败"), 1, 1);
            }
            sleep(1);
            $msg = $enterprise->changeSync(FALSE, 0);
            $log = DL('同步数据成功');
            $enterprise->log($log, 1, 0);
            echo json_encode($msg);
    }

    function iframe() {
            $this->htmlrender('_iframe.tpl');
    }

    function view() {
            $enterprise = new enterprise($_REQUEST);
            $users = new users($_REQUEST);
            $data = $enterprise->getByid();
            $this->smarty->assign('data', $data);
            $this->smarty->assign('ep', $data);
            $phone_num = $users->getusertotal(1);
            $dispatch_num = $users->getusertotal(2);
            $gvs_num = $users->getusertotal(3);
            $this->smarty->assign('phone_num', $phone_num);
            $this->smarty->assign('dispatch_num', $dispatch_num);
            $this->smarty->assign('gvs_num', $gvs_num);
            $this->smarty->assign('data', $data);
            $this->render('modules/enterprise/view.tpl', "企业信息");
    }

    function move_mds_item() {
            die;
    }

    function move_vcr_item() {
            die;
    }

    function index_item() {
            die;
    }

    function index_del() {
            die;
    }

    function save_shell() {
            die;
    }

    function stop() {
            die;
    }

    function start() {
            die;
    }

    function refresh() {
            die;
    }

    function initdb() {
            die;
    }

    function index() {
            die;
    }

    function add() {
            die;
    }

    function edit() {
            die;
    }

    function move_mds() {
            die;
    }

    function move_vcr() {
            die;
    }

}
