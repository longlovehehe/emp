<?php

/*
  require_once '../shell/class/dao/device.class.php';
  require_once '../shell/class/dao/enterprise.class.php';
  require_once '../shell/class/dao/area.class.php';
  require_once '../shell/class/contorl.class.php';
  require_once '../shell/class/page.class.php';
 */

class DeviceContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    function refresh ()
    {
        $device = new device ( $_REQUEST );
        $tools = new tools();
        $data = $device->get ();
        $resultlist = array ();
        try
        {
            $resultlist = $device->refreshList ();
            foreach ( $resultlist as $value )
            {
                $data['d_id'] = $value;
                $device->set ( $data );
                $deviceitem = $device->getByid ();

                try
                {
                    $tools->send ( "DevSave" , $deviceitem["d_id"] . ' ' . $deviceitem['d_ip1'] . ' ' . $deviceitem['d_port1'] );
                }
                catch ( Exception $ex )
                {
                    $device->updateStatus ( array ( 2 , $deviceitem['d_id'] ) );
                    throw new Exception ( $ex->getMessage () , 0 );
                }
            }
        }
        catch ( Exception $ex )
        {
            $tools->call ( $ex->getMessage () , 0 , true );
        }
        $tools->call ( "成功" , 0 , true );
    }

    function mds_option ()
    {
        $device = new device ( $_REQUEST );
        $list = $device->getMDSListOption ();
        if ( $_REQUEST['view'] != "" )
        {
            $this->smarty->assign ( 'list' , $list );
            $this->htmlrender ( 'modules/device/mds_option_view.tpl' );
        }
        else
        {

            $this->smarty->assign ( 'list' , $list );
            $this->htmlrender ( 'modules/device/mds_option.tpl' );
        }
    }

    function mds_item ()
    {
        $device = new device ( $_REQUEST );
        $page = new page ( $_REQUEST );
        $this->page = $page;
        $this->page->setTotal ( $device->getMDSTotal () );
        $list = $device->getMDSList ( $this->page->getLimit () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'list' , $list );
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );

        $this->htmlrender ( 'modules/device/mds_item.tpl' );
    }

    function mds_del ()
    {
        $device = new device ( $_REQUEST );
        $result[count] = $device->delMDSList ();
        echo $result[count];
    }

    function mds_id ()
    {
        $device = new device ( $_REQUEST );
        echo json_encode ( $device->GetJsonByMDSId () );
    }

    function mds_save ()
    {
        $device = new device ( $_REQUEST );
        try
        {
            $msg = $device->save ();
        }
        catch ( Exception $ex )
        {
            $this->tools->call ( "设备名称/设备外网地址 重复" , -1 , TRUE );
        }

        $data = $device->get ();
        switch ( $data["d_type"] )
        {
            case "mds":
            case "vcr":
                try
                {
                    $this->tools->send ( "DevSave" , $data["d_id"] . ' ' . $data['d_ip1'] . ' ' . $data['d_port1'] );
                }
                catch ( Exception $ex )
                {
                    $device->updateStatus ( array ( 2 , $data['d_id'] ) );

                    $device->log ( "设备保存失败:" . $ex->getMessage () , 2 , 2 );
                    throw new Exception ( '设备保存失败，请管理员检查日志' , 0 );
                }
                break;
            case "vcrs":
                $this->tools->send ( "DevVcrs" , $data["d_id"] );
                break;
        }
        echo json_encode ( $msg );
    }

    function device_list_item ()
    {
        $enterprise = new enterprise ( $_REQUEST );
        $result = $enterprise->getDeviceList ();

        $this->smarty->assign ( 'list' , $result['fetchall'] );
        $this->smarty->assign ( 'page' , $result['page'] );

        if ( $_REQUEST['do'] == 'mds' )
        {
            $this->htmlrender ( 'modules/device/mds_list_item.tpl' );
        }
        else
        {
            $this->htmlrender ( 'modules/device/vcr_list_item.tpl' );
        }
    }

    function vcr_item ()
    {
        $device = new device ( $_REQUEST );
        $this->page->setTotal ( $device->getVCRTotal () );
        $list = $device->getVCRList ( $this->page->getLimit () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'list' , $list );
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );
        $this->htmlrender ( 'modules/device/vcr_item.tpl' );
    }

    function vcr_del ()
    {
        $device = new device ( $_REQUEST );
        $result[count] = $device->delVCRList ( $_REQUEST["list"] );
        $device->log ( "VCR删除" , 2 , 0 , $_REQUEST );
        echo $result[count];
    }

    function vcr_save ()
    {
        $device = new device ( $_REQUEST );
        $msg = $device->save ();
        echo json_encode ( $msg );
        exit ();
    }

    function vcrs_item ()
    {
        $device = new device ( $_REQUEST );
        $this->page->setTotal ( $device->getVCRSTotal () );
        $list = $device->getVCRSList ( $this->page->getLimit () );
        $numinfo = $this->page->getNumInfo ();
        $prev = $this->page->getPrev ();
        $next = $this->page->getNext ();
        $this->smarty->assign ( 'list' , $list );
        $this->smarty->assign ( 'numinfo' , $numinfo );
        $this->smarty->assign ( 'prev' , $prev );
        $this->smarty->assign ( 'next' , $next );
        $this->htmlrender ( 'modules/device/vcrs_item.tpl' );
    }

    function vcrs_del ()
    {
        $device = new device ( $_REQUEST );
        $result["count"] = $device->delVCRSList ( $_REQUEST["list"] );
        $device->log ( "VCRS 删除了这些设备，数据流如下" . implode ( "," , $this->data ) , 2 , 0 );
        echo $result["count"];
    }

    function device_add ()
    {
        $this->smarty->assign ( 'data' , $_REQUEST );
        $this->render ( 'modules/device/device_add.tpl' , '新增设备' );
    }

    function device_edit ()
    {
        $device = new device ( $_REQUEST );
        $data = $device->getByid ();
        $this->smarty->assign ( 'data' , $data );
        $this->render ( 'modules/device/device_edit.tpl' , '编辑设备' );
    }

    function device_list ()
    {
        $this->smarty->assign ( 'data' , $_REQUEST );
        $this->render ( 'modules/device/device_list.tpl' , '使用详情' );
    }

    function vcr ()
    {
        $this->render ( 'modules/device/vcr.tpl' , 'VCR列表' );
    }

    function vcrs ()
    {
        $this->render ( 'modules/device/vcrs.tpl' , 'VCR-S列表' );
    }

    function index ()
    {
        $this->render ( 'modules/device/mds.tpl' , 'GQT-Server管理' );
    }

    function mds ()
    {
        $this->render ( 'modules/device/mds.tpl' , 'GQT-Server列表' );
    }

    function console ()
    {
        $this->render ( 'viewer/super_console.tpl' , '超级控制台' );
    }

}
