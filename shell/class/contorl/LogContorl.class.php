<?php

/*
  require_once '../shell/class/contorl.class.php';
  require_once '../shell/class/dao/area.class.php';
  require_once "../shell/class/page.class.php";
 */

class LogContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function index ()
    {
        $this->render ( 'modules/log/index.tpl' , '企业日志' );
    }

    public function index_item ()
    {
        $smarty = $this->smarty;
        $page = new page ( $_REQUEST );
        $log = new log ( $_REQUEST );
        $page->setTotal ( $log->getTotal () );
        $list = $log->getList ( $page->getLimit () );
        $numinfo = $page->getNumInfo ();
        $prev = $page->getPrev ();
        $next = $page->getNext ();
        $smarty->assign ( 'list' , $list );
        $smarty->assign ( 'numinfo' , $numinfo );
        $smarty->assign ( 'prev' , $prev );
        $smarty->assign ( 'next' , $next );
        $smarty->display ( 'modules/log/index_item.tpl' );
    }

}
