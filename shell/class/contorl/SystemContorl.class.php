<?php

/*
  require_once '../shell/class/contorl.class.php';
  require_once '../shell/class/dao/area.class.php';
  require_once '../shell/class/dao/enterprise.class.php';
 */

class SystemContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function index ()
    {
        die;
        $_REQUEST['d_area'] = '#';
        $system = new system ( $_REQUEST );
        $list = $system->getList ();
        $ep = new enterprise ( $_REQUEST );
        $device = new device ( $_REQUEST );
        $this->smarty->assign ( "en" , $ep->getTotal () );
        $this->smarty->assign ( "device" , $device->getMDSTotal () );
        $this->smarty->assign ( "list" , $list );
        $this->render ( 'modules/system/index.tpl' , "首页" );
    }

    public function person ()
    {
        $user = $_SESSION['om_id'];
        $this->smarty->assign ( "username" , $user );
        $this->render ( 'modules/system/person.tpl' , "个人信息查看" );
    }

    public function person_edit ()
    {
        $this->render ( 'modules/system/person_edit.tpl' , "个人信息查看" );
    }

    public function resetpassword ()
    {
        $this->render ( 'modules/system/resetpassword.tpl' , "修改密码" );
    }

    public function pro_details ()
    {
        $system = new system ( $_REQUEST );
        $data = $system->pro_details ();
        $this->smarty->assign ( "data" , $data );
        $this->render ( 'modules/system/pro_details.tpl' );
    }

    public function announcement ()
    {
        $this->render ( 'modules/system/announcement.tpl' , "标题" );
    }

    public function index_item ()
    {
        $system = new system ( $_REQUEST );
        $page = new page ( $_REQUEST );
        $page->setTotal ( $system->getAnTotal () );
        $getAnList = $system->getAnList ( $page->getLimit () );
        $this->smarty->assign ( 'getAnList' , $getAnList );
        $numinfo = $page->getNumInfo ();
        $prev = $page->getPrev ();
        $next = $page->getNext ();

        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );
        $this->htmlrender ( 'modules/system/index_item.tpl' );
    }

    public function changepassword ()
    {
        $system = new system ( $_REQUEST );
        $data = $system->chgPwd ();
        echo json_encode ( $data );
    }

}
