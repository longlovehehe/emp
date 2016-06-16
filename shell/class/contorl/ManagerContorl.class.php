<?php

/*
  require_once '../shell/class/contorl.class.php';
  require_once '../shell/class/page.class.php';
  require_once '../shell/class/sendmsg.class.php';

  require_once '../shell/class/dao/enterprise.class.php';
  require_once '../shell/class/dao/admins.class.php';
  require_once '../shell/class/dao/manager.class.php';
  require_once '../shell/class/dao/area.class.php';
  require_once "../shell/class/page.class.php";
  require_once '../shell/class/dao/admins.class.php';
 */

class ManagerContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function index ()
    {
        $this->smarty->assign ( 'title' , "角色管理" );
        $this->render ( 'modules/manager/index.tpl' , '角色管理' );
    }

    public function om_edit ()
    {
        $manager = new manager ( $_REQUEST );
        $list = $manager->getById ();
        $this->smarty->assign ( 'list' , $list );
        $this->render ( 'modules/manager/om_add.tpl' , '编辑运营管理员' );
    }

    public function om_add ()
    {
        $this->render ( 'modules/manager/om_add.tpl' , '新增运营管理员' );
    }

    public function index_item ()
    {
        $manager = new manager ( $_REQUEST );
        $page = new page ( $_REQUEST );
        $page->setTotal ( $manager->getTotal () );
        $list = $manager->getList ( $page->getLimit () );
        $numinfo = $page->getNumInfo ();
        $prev = $page->getPrev ();
        $next = $page->getNext ();
        $this->smarty->assign ( 'list' , $list );
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );
        $this->htmlrender ( 'modules/manager/index_item.tpl' );
        exit ();
    }

    public function om_save ()
    {
        $manager = new manager ( $_REQUEST );
        $msg = $manager->save ();
        echo json_encode ( $msg );
        exit ();
    }

    public function om_del ()
    {
        $manager = new manager ( $_REQUEST );
        $list = explode ( ',' , trim ( $this->tools->get ( "list" ) , ',' ) );
        $result["count"] = $manager->del ( $list );
        echo $result["count"];
        exit ();
    }

    public function om_reset ()
    {
        $manager = new manager ( $_REQUEST );
        $msg = $manager->reset ();
        echo json_encode ( $msg );
        exit ();
    }

}
