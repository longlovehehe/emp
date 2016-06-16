<?php

/*
  require_once '../shell/class/contorl.class.php';
  require_once '../shell/class/dao/area.class.php';
  require_once "../shell/class/page.class.php";
  require_once '../shell/class/dao/product.class.php';
 */

class ProductContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    public function getEListBypid ( $pid )
    {
        // 获取指定ID的产品功能
        $data = array ();
        $data['id'] = $pid;
        $product = new product ( $data );
        $product_item = $product->getbyid ();

        // 从产品中获取具有的功能名称
        $p_item = $product_item['p_items'];
        $p_item_array = explode ( '|' , $p_item );
        $list = array ();
        foreach ( $p_item_array as $item )
        {
            $item_array = explode ( ',' , $item );
            if ( $item_array[1] === '1' )
            {
                array_push ( $list , $item_array[0] );
            }
        }

        //查询对应功能名称
        $where = "pi_code IN ('" . implode ( "','" , $list ) . "')";
        $functionlist = $product->getFunctionList ( $where );

        //功能列表
        $function_str = L("功能列表")."：<br />";
        $function_arr = array ();
        foreach ( $functionlist as $item )
        {
            array_push ( $function_arr , L($item['pi_name']) );
        }
        $function_str .= implode ( '<br />' , $function_arr );
        return $function_str;
    }
    /**
     * 产品功能清单2
     * @param type $pid
     * @return type
     */
    public function getEListBypjson ( $pjson )
    {
        // 获取指定ID的产品功能
        
        if($pjson==NULL||$pjson=="%"){
            $data = array ();
        }else{
           $data = json_decode($pjson);  
        }
       foreach ($data as $key => $value) {
            if($value=='gn_yyhy'){
                unset($data[$key]);
            }
        }

        $product = new product();

        //查询对应功能名称
        $where = "pi_code IN ('" . implode ( "','" , $data ) . "')";
        $functionlist = $product->getFunctionList ( $where );

        //功能列表
        $function_str = L ( "功能列表" ) . "：<br />";
        $function_arr = array ();
        foreach ( $functionlist as $item )
        {
            array_push ( $function_arr , L($item['pi_name']) );
        }
        $function_str .= implode ( '<br />' , $function_arr );
        return $function_str;
    }

    public function option ()
    {
        $product = new product ( $_REQUEST );
        $result = $product->getEList ();

        foreach ( $result as $key => $value )
        {
            $result[$key]['id'] = &$value['p_id'];
            $result[$key]['name'] = &$value['p_name'];
        }
        $this->smarty->assign ( "list" , $result );
        $this->htmlrender ( 'viewer/option.tpl' );
    }

     /**
     * 产品功能获取接口
     * @return html_option 产品列表
     */
    public function p_option ()
    {
        $product = new product ( $_REQUEST );
        $result = $product->getPList ();
        foreach ( $result as $key => $value )
        {
            $result[$key]['id'] = &$value['pi_id'];
            $result[$key]['name'] = &$value['pi_name'];
        }
        $this->smarty->assign ( "list" , $result );
        $this->htmlrender ( 'viewer/price_option.tpl' );
    }

     /**
     * 产品功能获取接口
     * @return html_option 产品列表
     */
    public function ip_option ()
    {
        $product = new product ( $_REQUEST );
        $result = $product->getPList ();
        foreach ( $result as $key => $value )
        {
            if($value['pi_code']!="gn_yyhy"){
                $result[$key]['id'] = &$value['pi_id'];
                $result[$key]['name'] = &$value['pi_name'];
                $result[$key]['code'] = &$value['pi_code'];
            }else{
                unset($result[$key]);
            }
        }
        $this->smarty->assign ( "list" , $result );
        $this->htmlrender ( 'viewer/input.tpl' );
    }

    public function index ()
    {
        $this->render ( 'modules/product/index.tpl' , '产品管理' );
    }

    public function p_add ()
    {
        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $result = $product->function_list ();
        $smarty->assign ( "result" , $result );

        $this->render ( 'modules/product/p_add.tpl' , '新增产品' );
    }

    public function p_edit ()
    {
        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $data = $product->p_details ();
        $smarty->assign ( "product_info" , $data[0] );
        $smarty->assign ( "data" , $data );
        $this->render ( 'modules/product/p_edit.tpl' , '编辑产品' );
    }

    public function p_function ()
    {
        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $page = new page ( $_REQUEST );
        $data = $product->getPro ( $page->getLimit () );
        $smarty->assign ( "list" , $data );
        $this->render ( 'modules/product/p_function.tpl' , '产品功能库' );
    }

    public function index_item ()
    {
        $product = new product ( $_REQUEST );
        $smarty = $this->smarty;
        $page = new page ( $_REQUEST );
        $page->setTotal ( $product->getTotal () );
        $list = $product->getList ( $page->getLimit () );
        $numinfo = $page->getNumInfo ();
        $prev = $page->getPrev ();
        $next = $page->getNext ();
        $area = new area();//获取所有区域
        $get_area = $area->getAllList ();//得到所有区域
        foreach ( $get_area as $val )
        {
            $all_area[] = $val['am_id'];
        }
        for ( $i = 0; $i < count ( $list ); $i ++ )
        {
            if ( $list[$i]['p_area'] == "[\"#\"]" )
            {
                $p_area = $all_area;
            }
            else
            {
                $p_area = json_decode ( $list[$i]['p_area'] );
            }
            $area = $_SESSION['eown']['om_area'];

            if ( $area == "[\"#\"]" )
            {
                $area = $all_area;
            }
            else
            {
                $area = json_decode ( $area );
            }
            $res = $this->arr_get_diff ( $area , $p_area );
            $list[$i]['res'] = $res;
            $is_used = $product->getused ( $list[$i]['p_id'] );
            if ( $is_used !== false )
            {
                $list[$i]['is_used'] = 1;
            }
            else
            {
                $list[$i]['is_used'] = 0;
            }
        }
        $smarty->assign ( 'list' , $list );
        $smarty->assign ( 'numinfo' , $numinfo );
        $smarty->assign ( 'prev' , $prev );
        $smarty->assign ( 'next' , $next );
        $smarty->display ( 'modules/product/index_item.tpl' );
        exit ();
    }

    public function p_save ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->p_save ();
        echo json_encode ( $msg );
        exit ();
    }

    public function p_addData ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->p_addData ();
        echo json_encode ( $msg );
        exit ();
    }

    public function pro_del ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->pro_del ();
        echo json_encode ( $msg );
        exit ();
    }

    public function del_all ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->delAll ();
        echo json_encode ( $msg );
        exit ();
    }

    public function p_del ()
    {
        $product = new product ( $_REQUEST );
        $msg = $product->p_del ();
        echo json_encode ( $msg );
        exit ();
    }

    public function arr_get_diff ( $arr1 , $arr2 )
    {
        if ( count ( $arr1 ) >= count ( $arr2 ) )
        {
            $arr = array_diff ( $arr1 , $arr2 );
            $resarr = array_intersect ( $arr , $arr2 );
            if ( $resarr == null || $arr == null )
            {
                $res = 1;//ok
            }
            else
            {
                $res = 2;//有没有包含的区域
            }
        }
        else
        {
            $res = 2;
        }
        return $res;
    }

}
