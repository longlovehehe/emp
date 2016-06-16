<?php

/**
 * 区域控制器
 * @package EMP_Area_contorl
 * @require {@see contorl} {@see area} {@see area} {@see page}
 */
class AreaContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function index ()
    {
        die;
        $this->permissions ( $_SESSION['eown'] , TRUE );
        $this->render ( 'modules/area/index.tpl' , '区域管理' );
    }

    public function area_edit ()
    {
        die;
        $this->permissions ( $_SESSION['eown'] , TRUE );
        $area = new area ( $_REQUEST );
        $smarty = $this->smarty;
        $data = $area->getByid ();
        $smarty->assign ( 'data' , $data );
        $this->render ( 'modules/area/area_edit.tpl' , '编辑区域' );
    }

    public function area_add ()
    {
        die;
        $this->permissions ( $_SESSION['eown'] , TRUE );
        $smarty = $this->smarty;
        $smarty->assign ( 'data' , $_REQUEST );
        $this->render ( 'modules/area/area_add.tpl' , '新增区域' );
    }

    public function option ()
    {
        $area = new area ( $_REQUEST );
        $smarty = $this->smarty;
        $smarty->assign ( "list" , $area->getList () );
        $smarty->display ( 'modules/area/area_option.tpl' );
    }

    public function index_item ()
    {

        die;
        $area = new area ( $_REQUEST );
        $page = new page ( $_REQUEST );
        $smarty = $this->smarty;

        $page->setTotal ( $area->getTotal () );
        $list = $area->getList ( $page->getLimit () );
        $numinfo = $page->getNumInfo ();
        $prev = $page->getPrev ();
        $next = $page->getNext ();
        $smarty->assign ( 'list' , $list );
        $smarty->assign ( 'numinfo' , $numinfo );
        $smarty->assign ( 'prev' , $prev );
        $smarty->assign ( 'next' , $next );
        $smarty->display ( 'modules/area/index_item.tpl' );
    }

    public function area_del ()
    {
        die;
        $this->permissions ( $_SESSION['eown'] , TRUE );
        $area = new area ( $_REQUEST );
        $msg = $area->delList ();
        echo json_encode ( $msg );
        exit ();
    }

    public function area_save ()
    {
        die;
        $this->permissions ( $_SESSION['eown'] , TRUE );
        $area = new area ( $_REQUEST );
        $msg = $area->save ();
        echo json_encode ( $msg );
        exit ();
    }

}
