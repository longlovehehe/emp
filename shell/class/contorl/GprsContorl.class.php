<?php

/**
 * GPRS控制器类
 * @category OMP
 * @package OMP_Device_contorl
 * @require {@see device} {@see enterprise} {@see area} {@see contorl} {@see page}
 */
class GprsContorl extends contorl
{   

    public $gprs;
    public $page;
    public $groups;
    public $ag;
    /**
     * 构造器，继承至contorl
     */
    public function __construct ()
    {
        parent::__construct ();
        $this->gprs = new gprs ( $_REQUEST );
        $this->page = new page ( $_REQUEST );
        $this->groups=new groups($_REQUEST);
        $this->ag=new agents($_REQUEST);
    }

   /*
    *流量卡列表页
    */
    public function index ()
    {
        $this->render ( 'modules/gprs/index.tpl' , '流量卡管理' );
    }

    /**
     * 流量卡列表内容页
     * @return html_table 流量卡列表
     */
    public function gprs_item ()
    {
        $page = new page ( $_REQUEST );
        $this->page = $page;
        $this->page->setTotal ( $this->gprs->getGprsTotal () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );

        $list = $this->gprs->getList ( $this->page->getLimit () );
        //$agent = new agents ( $_REQUEST );
        //var_dump($list);
        //var_dump($_SESSION);
        $this->smarty->assign ( 'list' , $list );
        $this->htmlrender ( 'modules/gprs/gprs_item.tpl' );
    }

    /**
     * 所属企业option生成
     * 所有流量卡中出现过的企业ID
     */
    public function e_option(){
        $list=$this->gprs->get_alllist();
        foreach($list as $key=>$value){
            if($value['g_e_id']!=""){
                $arr[$key]['id']=$value['g_e_id'];
                $arr[$key]['name']=$value['e_name'];
            }
        }

        $arr=array_unique_fb($arr);
        $this->smarty->assign('list',$arr);
        $this->htmlrender("viewer/option.tpl");
    }

    /**
     * 流量卡历史记录页面
     */
    public function history_gprs(){
        $mininav = array(
            array(
                "url" => "?m=gprs&a=index",
                "name" => "流量卡管理",
                "next" => ">>",
            ),
            array(
                "url" => "#",
                "name" => "历史纪录",
                "next" => "",
            ),
        );
        //获取终端信息
        $this->gprs->set(array('g_iccid'=>$_REQUEST['g_iccid'],'g_id'=>$_REQUEST['g_id']));
        $info=$this->gprs->getselect_list();
        $this->smarty->assign('mininav', $mininav);
        $this->smarty->assign('data', $info);
        $this->render("modules/gprs/gprs_history.tpl",L('历史纪录'));
    }
    
     /**
     * l流量卡史记录列表页
     */
    public function history_item(){
        $this->page->setTotal($this->gprs->getTotal_history());
        $list = $this->gprs->getList_gprs_history($this->page->getLimit());
        //var_dump($list);die;
        $numinfo = $this->page->getNumInfo();
        $prev = $this->page->getPrev();
        $next = $this->page->getNext();
        $this->smarty->assign('list', $list);
        $this->smarty->assign('numinfo', $numinfo);
        $this->smarty->assign('prev', $prev);
        $this->smarty->assign('next', $next);
        $this->htmlrender("modules/gprs/gprs_history_item.tpl");
    }
    
    /**
     * 状态设置
     */
    public function set_stat(){
        $res=$this->gprs->set_stat();
        echo json_encode($res);
    }

    /**
     * 所属代理商option生成
     * 所有的一级代理商ID
     */
    public function ag_option(){
        $list=$this->gprs->getAllag();
        foreach($list as $key=>$value){
            $arr[$key]['id']=$value['ag_number'];
            $arr[$key]['name']=$value['ag_name'];
        }
        $arr=array_unique_fb($arr);
        $this->smarty->assign('list',$arr);
        $this->htmlrender("viewer/option.tpl");
    }
    /**
     * 编辑页面
     */
    public function gprs_edit ()
    {
        $mininav = array (
            array (
                "url" => "?m=gprs&a=index" ,
                "name" => "流量卡管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "#" ,
                "name" => "编辑" ,
            )
        );
        $info = $this->gprs->getByid ();
        $this->smarty->assign ( 'mininav' , $mininav );
        $this->smarty->assign ( 'data' , $info );
        //var_dump($info);
        $this->render ( 'modules/gprs/gprs_edit.tpl' , '编辑流量卡' );
    }

    /**
   * 检测iccid,imsi,number是否已存在
   */
  public function check_edit(){
      $res = $this->gprs->getById_list();
      echo $res;
  }
  //保存流量卡的修改
  public function save_gprs(){
      $msg=$this->gprs->save_gprs();
      echo json_encode($msg);
  }
  //删除流量卡
  public function del_term(){
     $aRes=$this->tem->getById_list();
     if($aRes['md_binding'] == '1')
     {
         echo 2;
     }
     else 
     {
        $res=$this->tem->term_del();
        echo $res;
     }
     
  }

    /**
     * 流量卡模板下载
     */
    public function gprs_export ()
    {
        $data = array ();
        //$data['e_id'] = filter_input ( INPUT_GET , 'e_id' );
        //var_dump($ug_list);

        $excel = new PHPExcel();

        $excel->getActiveSheet()->getColumnDimension("A")->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension("B")->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension("C")->setWidth(25);
        /** 设置表头 */
        $excel->getActiveSheet ()->setCellValue ( 'A1' , 'ICCID' );
        $excel->getActiveSheet ()->setCellValue ( 'B1' , 'IMSI' );
        $excel->getActiveSheet ()->setCellValue ( 'C1' , 'Number' );
        //$excel->getActiveSheet ()->setCellValue ( 'D1' , '套餐' );
        //$excel->getActiveSheet ()->setCellValue ( 'E1' , '开卡日' );

        $excel->getActiveSheet ()->setCellValueExplicit ( "A" . 2 , "1010101010101010101" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "B" . 2 , "101010101010101" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "C" . 2 , "13700000000" , PHPExcel_Cell_DataType::TYPE_STRING );
        
        $excel->getActiveSheet ()->setCellValueExplicit ( "A" . 3 , "12345678906666666666" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "B" . 3 , "123456789222222" , PHPExcel_Cell_DataType::TYPE_STRING );
        $excel->getActiveSheet ()->setCellValueExplicit ( "C" . 3 , "8622222355029" , PHPExcel_Cell_DataType::TYPE_STRING );
        /* 导出 */
        coms::head ( 'excel' , $excel );

}

    /**
     * 流量卡导入
     */
    public function importShellICCID ()
    {
        $step = is_string ( $_REQUEST['step'] ) ? $_REQUEST['step'] : '';
        if ( $step === 'if' )
        {
            $msg = $this->importPTFile ();
            print "<script>parent.tm_if_callback(" . $msg . ")</script>";
            exit;
        }
        if ( $step === 'ic' )
        {
            try
            {
                $f = $this->importICCIDCheck ();
                if ( count ( $this->error ) > 0 )
                {
                    $json['status'] = -1;
                    $json['msg'] = '存在错误无法导入<br />';
                }
                else
                {
                    $json['status'] = 0;
                    $json['msg'] = '无严重错误<br />';
                }
                $json['msg'].='<div class="show">';
                $json['msg'] .= implode ( '<br />' , $this->error );
                //$json['msg'] .= "<hr />";
                //$json['msg'] .= implode ( '<br />' , $this->warn );
                $json['msg'].='</div>';

                $json['data'] = $f;
                $msg = json_encode ( $json );
            }
            catch ( Exception $ex )
            {
                $json['status'] = -1;
                $json['msg'] = $ex->getMessage ();
                $msg = json_encode ( $json );
            }
            print "<script>parent.tm_ic_callback(" . $msg . ")</script>";
            exit;
        }
        if ( $step === 'i' )
        {
            try
            {
                $this->importPT ();

                if ( count ( $this->error ) > 0 )
                {
                    $json['status'] = -1;
                    $json['msg'] = '存在错误';
                    $json['msg'].='<div class="show">';
                    $json['msg'] .= implode ( '<br />' , $this->error );
                    $json['msg'].='</div>';
                }
                else
                {
                    $json['status'] = 0;
                    $json['msg'] = '没有发现错误，导入完成';
                }

                $msg = json_encode ( $json );
            }
            catch ( Exception $ex )
            {
                $json['status'] = -1;
                $json['msg'] = $ex->getMessage ();
                $msg = json_encode ( $json );
            }
            print "<script>parent.tm_i_callback(" . $msg . ")</script>";
            exit;
        }
    }

    /**
     * 流量卡导入检查
     * @return string
     * @throws Exception
     */
    private function importICCIDCheck ()
    {
        $f = filter_input ( INPUT_GET , 'f' );
        $e_id = filter_input ( INPUT_GET , 'e_id' );
        $file = $f . '.xls';
        $config = Cof::config ();
        $filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel5' );

        $objPHPExcel = $objReader->load ( $filePath );
        $objWorksheet = $objPHPExcel->getSheet ( 0 );

        //$highestColumn = $objWorksheet->getHighestColumn();
        $highestRow = $objWorksheet->getHighestRow ();    //取得总行数
        $pttm = array ();
        $error = array ();
        $warn = array ();
        $ptnumber = array ();
        $wz = "";
        for ( $row = 2; $row <= $highestRow; $row ++ )
        {
            /*$tmpName = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            if ( $tmpName !== '' )
            {
                $wz = $tmpName;

                if ( ! Cof::re ( ' /^1\d{10}$/' , $tmpName , 64 ) )
                {
                    $error[] = "第 $row 行，$tmpName 不是手机号";
                }
            }*/
            $tmpuser = array ();
            $tmpuser['g_iccid'] = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            $tmpuser['g_imsi'] = trim ( $objWorksheet->getCellByColumnAndRow ( 1 , $row )->getValue () );
            $tmpuser['g_number'] = trim ( $objWorksheet->getCellByColumnAndRow ( 2 , $row )->getValue () );
            //$tmpuser['g_belong'] = trim ( $objWorksheet->getCellByColumnAndRow ( 2 , $row )->getValue () );
            //$tmpuser['g_packages'] = trim ( $objWorksheet->getCellByColumnAndRow ( 3 , $row )->getValue () );
            //$tmpuser['g_start_time'] = trim ( $objWorksheet->getCellByColumnAndRow ( 4 , $row )->getValue () );
            //var_dump ( $tmpuser['g_iccid'] );die;

            if ( ! Cof::re ( '/^\d{19}$|^\d{20}$/i' , $tmpuser['g_iccid'] ) )
            {
                $error[] = "第 $row 行，" . $tmpuser['g_iccid'] . " iccid格式不对，为19或20位数字";
            }

            if ( ! Cof::re ( '/^\s*$|^[0-9]{15}$/i' , $tmpuser['g_imsi'] ) )
            {
                $error[] = "第 $row 行，" . $tmpuser['g_imsi'] . " g_imsi格式不对,为15位数字";
            }

            if ( ! Cof::re ( '/^\s*$|^1\d{10}$/' , $tmpuser['g_number'] ) )
            {
                $error[] = "第 $row 行，" . $tmpuser['g_number'] . " g_number格式不对,11位手机号";
            }

            if($tmpuser['g_iccid'] != ''){
                $info = $this->gprs->checkexcel($tmpuser['g_iccid'],'','');
                if($info){
                    $error[] = "第 $row 行，" . $tmpuser['g_iccid'] . " iccid已存在";
                }
            }

            if($tmpuser['g_imsi'] != ''){
                $info = $this->gprs->checkexcel('',$tmpuser['g_imsi'],'');
                if($info){
                    $error[] = "第 $row 行，" . $tmpuser['g_imsi'] . " imsi已存在";
                }
            }

            if($tmpuser['g_number'] != ''){
                $info = $this->gprs->checkexcel('','',$tmpuser['g_number']);
                if($info){
                    $error[] = "第 $row 行，" . $tmpuser['g_number'] . " number手机号已存在";
                }
            }


            /*if ( ! Cof::re ( '/^[\d]+$/' , $tmpuser['g_start_time'] ) )
            {
                $warn[] = "警告 第 $row 行，开卡日期" . $tmpuser['g_start_time'] . " 不符合规范。（如:20150203）";
            }*/
            $pttm[$wz][] = $tmpuser;
        }

        //$this->warn = $warn;
        $this->error = $error;
        return $f;
    }

    // 导入文件
    private function importPTFile ()
    {
        $json = array ();
        try
        {
            $file = Cof::upload ();
            $json['status'] = 0;
            $json['data'] = str_replace ( '.xls' , '' , $file ); //清除后缀信息
        }
        catch ( Exception $ex )
        {
            $json['status'] = -1;
            $json['msg'] = $ex->getMessage ();
        }
        return json_encode ( $json );
    }

    // 数据导入
    private function importPT ()
    {
        $e_id = filter_input ( INPUT_GET , 'e_id' );
        $f = filter_input ( INPUT_GET , 'f' );
        $file = $f . '.xls';
        $config = Cof::config ();
        $filePath = $config['system']['webroot'] . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $file;
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel5' );
        $objPHPExcel = $objReader->load ( $filePath );
        $objWorksheet = $objPHPExcel->getSheet ( 0 );

        $highestRow = $objWorksheet->getHighestRow ();    //取得总行数
        // 实际数据读取，数据导入
        $pttm = array ();
        $error = array ();
        $warn = array ();
        $ptnumber = array ();
        $wz = "";
        for ( $row = 2; $row <= $highestRow; $row ++ )
        {
            /*$tmpName = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            if ( $tmpName !== '' )
            {
                $wz = $tmpName;

                if ( ! Cof::re ( ' /^1\d{10}$/' , $tmpName , 64 ) )
                {
                    $error[] = "第 $row 行，$tmpName 不是手机号";
                }
            }*/
            $tmpuser = array ();
            $tmpuser['g_iccid'] = trim ( $objWorksheet->getCellByColumnAndRow ( 0 , $row )->getValue () );
            $tmpuser['g_imsi'] = trim ( $objWorksheet->getCellByColumnAndRow ( 1 , $row )->getValue () );
            $tmpuser['g_number'] = trim ( $objWorksheet->getCellByColumnAndRow ( 2 , $row )->getValue () );
            //$tmpuser['g_belong'] = trim ( $objWorksheet->getCellByColumnAndRow ( 2 , $row )->getValue () );
            //$tmpuser['g_packages'] = trim ( $objWorksheet->getCellByColumnAndRow ( 3 , $row )->getValue () ) == "1.2G" ? 1 : 2;
            //$tmpuser['g_start_time'] = trim ( $objWorksheet->getCellByColumnAndRow ( 4 , $row )->getValue () );
            
            $pttm[$row][] = $tmpuser;
            $this->warn = $warn;
            $this->error = $error;
        }
        //var_dump($pttm);die;
        // 导入流量卡
        $gprs = new gprs ();
        $pgnumber = array ();
        foreach ( $pttm as $key => $value )
        {
            $data = array ();
            $data['do'] = 'add';
            $data['g_iccid'] = $value[0]['g_iccid'];
            $data['g_imsi']=$value[0]['g_imsi'];
            $data['g_number']=$value[0]['g_number'];
            $data['g_add_user']=$_SESSION['own']['om_id'];
            $data['g_binding']='0';
            $data['g_status']='2';
            //$data['g_belong'] = $value[0]['g_belong'];
            //$data['g_packages'] = $value[0]['g_packages'];
            //$data['g_start_time'] = $value[0]['g_start_time'];
            $data['g_intime'] = date ( "Y-m-d H:i:s" , time ());
            //$data['g_final_user'] = $_SESSION['own']['om_id'];
            $data['g_agents_id'] = "0";
            //$data['g_stock_status'] = 1;
            $gprs->set ( $data );
            try
            {
                $gprs->save_gprs ();
            }
            catch ( Exception $exc )
            {
                if ( $exc->getCode () == 23505 )
                {
                    throw new Exception ( "所导入的ICCID已经存在,请检查" );
                }
            }
        //if ( $msg['status'] == '0' )
            // {
            //    $pgnumber[$key] = $e_id . sprintf ( "%05d" , $tmppgnumber );
            // }
        }
        $error = array ();
    }

    public function gprs_item_v2 ()
    {
        $page = new page ( $_REQUEST );
        $this->page = $page;
        $this->page->setTotal ( $this->gprs->getGprsTotal () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );


        $list = $this->gprs->getList_v2 ();
        $agent = new agents ( $_REQUEST );
        $aginfo = $agents->get;
        $this->smarty->assign ( 'list' , $list );
        $this->htmlrender ( 'modules/gprs/gprs_item_v2.tpl' );
    }

    /**
     * 入库页面
     */
    public function gprs_add ()
    {
        $mininav = array (
            array (
                "url" => "?m=gprs&a=index" ,
                "name" => "流量卡管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "?m=gprs&a=gprs_add" ,
                "name" => "流量卡入库" ,
                "next" => ""
            )
        );

        $this->smarty->assign ( 'mininav' , $mininav );
        $this->render ( 'modules/gprs/gprs_add.tpl' , '办理入库' );
    }

    /**
     * 出库页面
     */
    public function gprs_out ()
    {
        $page = new page ( $_REQUEST );
        $this->page = $page;
        $this->page->setTotal ( $this->gprs->getGprsTotal () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );


        $list = $this->gprs->getList ( $this->page->getLimit () );
        $this->smarty->assign ( 'list' , $list );
        $mininav = array (
            array (
                "url" => "?m=gprs&a=index" ,
                "name" => "流量卡管理" ,
                "next" => ">>"
            ) ,
            array (
                "url" => "?m=gprs&a=gprs_add" ,
                "name" => "流量卡出库" ,
                "next" => ""
            )
        );

        $this->smarty->assign ( 'mininav' , $mininav );
        $this->render ( 'modules/gprs/gprs_out.tpl' , '办理出库' );
    }

//流量卡出库{1.代理商2.企业}
    public function gprsshellout ()
    {
        if ( $_REQUEST['create_type'] == 'agents' )//1.代理商出库
        {
            foreach ( $_REQUEST["checkbox"] as $val )
            {
                $data['g_iccid'] = $val;
                $gprs = new gprs ( array ( 'g_iccid' => $val ) );
                //$agents = new agents ( array ( 'ag_number' => $_SESSION['ag']['ag_number'] ) );
                $info = $gprs->getByid ();
                // $ag_info = $agents->getByid ();
                $data['g_agents_id'] = $_REQUEST['g_ag_id'];
                $data['g_agents_assign'] = $info['g_agents_assign'] . "|" . $data['g_agents_id'] . "|";
                $data['g_outtime'] = date ( 'Y-m-d' , time () );
                $data['g_intime0'] = $data['g_outtime'];
                $data['g_final_user'] = $_REQUEST['g_final_user'];
                $data['g_stock_status'] = 2;
                $this->gprs->set ( $data );
                $this->gprs->gprsshellout ();
            }
        }
        else//2.企业出库
        {
            if ( $_REQUEST['g_ag_en_id'] == "" )
            {
                //1.创建企业,并设置管理员
                $ep = new enterprise ( $_REQUEST );
                $result = $ep->save ();
                $data['em_id'] = $result['e_id'];
                $data['em_pswd'] = $data['em_id'];
                $data['em_ent_id'] = $data['em_id'];
                $data['em_phone'] = $_REQUEST['em_phone'];
                $data['em_mail'] = $_REQUEST['em_mail'];
                $data['em_name'] = $_REQUEST['em_name'];
                $data['em_desc'] = "";
                $data['edit'] = '';
                $e_id = $result['e_id'];
                /*                 * **创建管理员*** */
                $admins = new admins ( $data );
                $admins->save ();

                $user = new users ( array ( 'e_id' => $result['e_id'] ) );
                $start_id = $user->getstartid ();
            }
            else//选择已有企业
            {
                /*                 * **批量创建手机用户并设置流量卡ICCID 自动登录*** */
                //①获得当前用户号码起始ID
                //初始化数据
                $user = new users ( array ( 'e_id' => $_REQUEST['g_ag_en_id'] ) );
                $start_id = $user->getstartid ();
                $e_id = $_REQUEST['g_ag_en_id'];
            }
            $start_num = substr ( $start_id[0] , 6 , 1 );
            $start_index = substr ( $start_id[0] , 0 , 1 );
            if ( $start_index == 1 )
            {
                $start_id = 70000;
            }
            else
            {
                if ( $start_num < 7 || count ( $start_id ) == 0 )
                {
                    $start_id = 70000;
                }
                else
                {
                    $start_id = $start_id[0] + 1;
                }
            }
            //②获取批量创建个数
            $sum = $_REQUEST['check_num'];
            //③创建用户,并分配流量卡ICCID 自动登录
            for ( $i = 0; $i < $sum; $i ++ )
            {
                $data['u_number'] = $start_id + $i;
                $data['u_passwd'] = $start_id + $i;
                $data['u_name'] = $start_id + $i;
                $data['u_iccid'] = $_REQUEST["checkbox"][$i];
                $data['u_auto_config'] = 1;
                $data['u_sex'] = "M";
                $data['e_id'] = $e_id;
                $data['u_sub_type'] = 1;
                $user->set ( $data );
                $user->save ();
            }
            /*             * ***** ********************************************** */
            //2.流量卡出库
            foreach ( $_REQUEST["checkbox"] as $val )
            {
                $data['g_iccid'] = $val;
                $gprs = new gprs ( array ( 'g_iccid' => $val ) );
                //$agents = new agents ( array ( 'ag_number' => $_SESSION['ag']['ag_number'] ) );
                $info = $gprs->getByid ();
                // $ag_info = $agents->getByid ();
                $data['g_agents_id'] = $_REQUEST['ag_number'];
                $data['g_agents_assign'] = $info['g_agents_assign'] . "|" . $data['g_agents_id'] . "|";
                $data['g_outtime'] = date ( 'Y-m-d' , time () );
                $data['g_intime0'] = $data['g_outtime'];
                $data['g_final_user'] = $_REQUEST['g_final_user'];
                $data['g_e_id'] = $e_id;
                $data['g_stock_status'] = 2;
                $this->gprs->set ( $data );
                $this->gprs->gprsshellout ();
            }
            /*             * ******************************** */
        }
        $this->tools->call ( "操作成功！" , 0 , true );
    }

//流量卡入库 OMP特有
    public function gprs_save ()
    {
        if ( $_REQUEST['do'] != "edit" )
        {
            //$_REQUEST['ag_number'] = $_REQUEST['g_final_user'];

            //$agents = new agents ( $_REQUEST );
            //$list = $agents->getByid ();
            $arr_iccid = array ();
            $arr_packages = array ();
            $arr_start_time = array ();
            $arr_intime = array ();
            $arr_belong = array ();
            foreach ( $_REQUEST as $key => $value )
            {
                if ( strstr ( $key , "g_iccid" ) )
                {
                   //$_REQUEST['g_iccid'] = array ();
                    array_push ( $arr_iccid , $value );
                }
                if ( strstr ( $key , "g_packages" ) )
                {
                    array_push ( $arr_packages , $value );
                }
                if ( strstr ( $key , "g_start_time" ) )
                {
                    //$_REQUEST['g_start_time'] = array ();
                    array_push ( $arr_start_time , $value );
                }
                if ( strstr ( $key , "g_intime" ) )
                {
                    //$_REQUEST['g_intime'] = array ();
                    array_push ( $arr_intime , $value );
                }
                if ( strstr ( $key , "g_belong" ) )
                {
                   //$_REQUEST['g_belong'] = array ();
                    array_push ( $arr_belong , $value );
                }
            }
            $_REQUEST['g_iccid'] = $arr_iccid;
            $_REQUEST['g_packages'] = $arr_packages;
            $_REQUEST['g_start_time'] = $arr_start_time;
            $_REQUEST['g_intime'] = $arr_intime;
            $_REQUEST['g_belong'] = $arr_belong;
            for ( $i = 0; $i < count ( $_REQUEST['g_iccid'] ); $i ++ )
            {
                $data['g_final_user'] = $_REQUEST['g_final_user'];
                $data['g_iccid'] = $_REQUEST['g_iccid'][$i];
                $data['g_packages'] = $_REQUEST['g_packages'][$i];
                $data['g_start_time'] = $_REQUEST['g_start_time'][$i];
                $data['g_intime'] = $_REQUEST['g_intime'][$i];
                $data['g_belong'] = $_REQUEST['g_belong'][$i];
                $data['g_agents_assign'] = "|0|";
                $data['g_agents_id'] = 0;
                $data['g_stock_status'] = 1;
                $data['g_recorder'] = $_REQUEST['g_final_user'];
                $data['do'] = $_REQUEST['do'];

                $this->gprs->set ( $data );
                if ( $data['g_iccid'] != "" && $data['g_belong'] != "" )
                {
                    $this->gprs->save_gprs ();
                }
            }
        }
        else
        {
            //$_REQUEST['ag_number'] = $_REQUEST['g_final_user'];
            $data['g_final_user'] = $_REQUEST['g_final_user'];
            $data['g_iccid'] = $_REQUEST['g_iccid'][0];
            $data['g_packages'] = $_REQUEST['g_packages'][0];
            $data['g_start_time'] = $_REQUEST['g_start_time'][0];
            $data['g_intime'] = $_REQUEST['g_intime'][0];
            $data['g_belong'] = $_REQUEST['g_belong'][0];
            $data['do'] = $_REQUEST['do'];
            $this->gprs->set ( $data );
            $this->gprs->save_gprs ();
        }
        $this->tools->call ( "操作成功" , 0 , true );
    }

    public function gprs_option ()
    {
        $gprs = new gprs ( $_REQUEST );
        $list = $gprs->getgprsList ();
        $this->smarty->assign ( 'list' , $list );
        $this->htmlrender ( 'modules/gprs/gprs_option_view.tpl' );
    }
    //流量卡入库
    public function batch_gprs(){
        foreach ($_REQUEST["g_iccid"] as $key => $value) {
            $data['g_iccid']=$value;
            $data['g_imsi']=$_REQUEST['g_imsi'][$key];
            $data['g_number']=$_REQUEST['g_number'][$key];
            if($_REQUEST['g_agents_id'][$key]=='' || $_REQUEST['g_agents_id'][$key]=='0'){
                $data['g_agents_id']="0";
            }else{
                $data['g_agents_id']=$_REQUEST['g_agents_id'][$key];
            }
            
            $data['g_binding']='0';
            $data['g_status']='2';
            $data['g_add_user']=$_SESSION['own']['om_id'];
            $data['g_intime']=date('Y-m-d H:i:s',time());
            $this->gprs->set($data);
            $msg=$this->gprs->save_gprs();
        }
        $this->tools->call($msg['msg'], $msg['status'], true);
  }
    //批量删除流量卡
    public function batch_del_gprs(){
          $res=0;
          foreach ($_REQUEST['checkbox'] as $key => $value) {
              $data['g_id']=$value;
              $this->gprs->set($data);
              $aRes=$this->gprs->getselect_list();
              if($aRes['g_binding'] == '0') 
              {
                $this->gprs->gprs_del();
                $res++;
              }
              
          }
            echo $res;
    }
    //流量卡批量绑定代理商
    public function bind_gprs(){
        $res=0;
        $args = explode(',',rtrim($_REQUEST['g_ids'],','));
        $data['agents'] = $_REQUEST['agents'];
        foreach ($args as $key => $value) {
            $data['g_id']=$value;
            $this->gprs->set($data);
            //$aRes=$this->gprs->getselect_list();
            //if($aRes['g_binding'] == '0') 
            //{
            $this->gprs->gprs_binds();
            $res++;
           // }
        }
        echo $res;
    }

}
