<?php

/**
 * 计算部门人总数
 */
function modCountUserGroupsTotal ( $ug_id )
{
    $data = array ();
    $data['u_ug_id'] = $ug_id;
    $user = new users ( $data );
    $total = $user->getTotal ( TRUE );
    return $total;
}

function getEListBypid ( $pid )
{
    $product_contorl = new ProductContorl;
    return $product_contorl->getEListBypid ( $pid );
}
function getEListBypjson($productjson) {
    $product_contorl = new ProductContorl();
    return $product_contorl->getEListBypjson($productjson);
}

/**
 */
function isDate ( $str )
{

    return date ( 'Y-m-d H:i:s' , strtotime ( $str ) );
}

function getDateRange ( $start , $end )
{
    $start = isDate ( $start );
    $end = isDate ( $end );
    $where = <<<SQL
                BETWEEN to_timestamp('{$start}', 'yyyy-mm-dd HH24:MI:SS') AND to_timestamp('{$end}', 'yyyy-mm-dd HH24:MI:SS')
SQL;
    return $where;
}

function isPhone ( $phone )
{
    $isPhone = "/^1[3-5,8]{1}[0-9]{9}$/";
    return preg_match ( $isPhone , $phone );
}

function modlang ()
{
    return "1";
}

function modpg_record_mode ( $code )
{
    switch ( $code )
    {
        case 2:
            return '不录音';
        case 0:
            return '对讲频道全程录音';
        case 1:
            return '根据话权方的录音标志录音';
    }
}

function modface ( $img )
{
    if ( $img == "" )
    {
        return L ( "无头像" );
    }
    else
    {
        return "<img class=\"face\" src=\"?m=enterprise&a=users_face_item&pid=$img\" />";
    }
}

function modsex ( $status )
{
    if ( $status == 'F' )
    {
        return L ( "女" );
    }
    else if ( $status == 'M' )
    {
        return L ( "男" );
    }
}

function modtype ( $str )
{
    switch ( $str )
    {
        case "1":
            return L ( "手机用户" );
        case "2":
            return L ( "调度台用户" );
        case "3":
            return L ( "GVS用户" );
        default :
            return L ( "未知" );
    }
}

function modifierStatus ( $status )
{
    switch ( $status )
    {
        case 0:
                return L("不启用");
        case 1:
                return L("启用");
        case 2:
                return L("发布处理中");
        case 3:
                return L("发布失败");
        case 4:
                return L("发布失败");
        case 5:
                return L("企业创建中");
        case 6:
                return L("企业删除中");
        case 7:
                return L("企业迁移中");
        case 8:
                return L("企业迁移失败");
        case 9:
                return L("企业创建失败");
    }
}

function modDeviceStatus ( $str )
{
    switch ( $str )
    {
        case 0:
            return L ( "处理中" );
        case 1:
            return L ( "正常" );
        case 2:
            return L ( "异常" );
    }
}

function modifierStorage ( $falg )
{
    switch ( $falg )
    {
        case 1:
            return L ( "同步" );
        case 2:
            return L ( "存储" );
        default :
            return L ( "无存储功能" );
    }
}

function modifierSafeLogin ( $safelogin )
{
    if ( $safelogin != 1 )
    {
        return L ( "不需要" );
    }
    else
    {
        return L ( "需要安全登录" );
    }
}

function mkdir_r ( $dirName , $rights = 0777 )
{
    $dirs = explode ( '/' , $dirName );
    $dir = '';
    foreach ( $dirs as $part )
    {
        $dir.=$part . '/';
        if ( ! is_dir ( $dir ) && strlen ( $dir ) > 0 )
        {
            mkdir ( $dir , $rights );
        }
    }
}

function modActionNameLog ( $action )
{
    switch ( $action )
    {
        case 'enterprise':
            return 1;
        case 'device':
            return 2;
        case 'manager':
            return 3;
        case 'area':
            return 4;
        case 'product':
            return 5;
        case 'logout':
        case 'login_check':
        case 'login':
            return 7;
        case 'announcement':
            return 8;
        default :
            return 6;
    }
}

function modmdsid ( $str )
{
    $data['d_id'] = $str;
    $device = new device ( $data );
    $data['e_id'] = $_REQUEST['e_id'];
    $ep = new enterprise ( $data );
    $epdata = $ep->getByid ();
    $data = $device->GetJsonByMDSId ();

    $data['diff_user'] += $epdata['e_mds_users'];
    $data['diff_call'] += $epdata['e_mds_call'];
    $data['name'] = $data['d_name'] . '【' . $data['d_ip1'] . '】' . '可用用户数：' . $data['diff_user'] . '|可用并发数：' . $data['diff_call'];
    return '[' . json_encode ( $data ) . ']';
}

function modugpath ( $str )
{
    $str = preg_replace ( "/[0-9]/" , '' , $str );
    $str = str_replace ( "||" , '—' , $str );
    return "|" . $str;
}

function modusercall ( $num )
{
    if ( $num < 0 )
    {
        return 0;
    }
    else
    {
        return $num;
    }
}

function L ( $node , $flag = TRUE )
{
    $res = coms::$res;
    $result = '';
    if ( $res[$node] == '' )
    {
        return $node;
    }
    else
    {
        return $res[$node];
    }
    if ( $flag )
    {
        return $result;
    }
    echo $result;
}
function DL ( $node , $flag = TRUE )
{
    $diff_res = coms::$diff_res;
    $result = '';
    if ( $diff_res[$node] == '' )
    {
        return $node;
    }
    else
    {
        return $diff_res[$node];
    }
    if ( $flag )
    {
        return $result;
    }
    echo $result;
}

function modrand ( $max )
{
    return rand ( 1 , $max );
}

function isadmin ( $str )
{
    if ( $_SESSION['eown']['om_id'] == 'admin' )
    {
        return $str;
    }
    return "";
}

function isallarea ( $str )
{
    if ( $_SESSION['eown']['om_area'] == '["#"]' )
    {
        return $str;
    }
    return "";
}

function notadmin ( $str )
{
    if ( $_SESSION['eown']['om_id'] != 'admin' )
    {
        return $str;
    }
    return "";
}

function level ( $level )
{
    switch ( $level )
    {
        case 'admin':
            return L ( "超级管理员" );
        default :
            return L ( "普通管理员" );
    }
}

function logLevel ( $falg )
{
    switch ( $falg )
    {
        case 1:
            return "<span class='warn log'><em></em><a href='?m=log&a=index&el_level=1'>".L('警告')."</a></span>";
        case 2:
            return "<span class='error log'><em></em><a href='?m=log&a=index&el_level=2'>".L('错误')."</a></span>";
        case 0;
            return "<span class='info log'><em></em><a href='?m=log&a=index&el_level=0'>".L('信息')."</a></span>";
    }
}

function logType ( $falg )
{
    switch ( $falg )
    {
        case 1:
            return L ( "用户" );
        case 2:
            return L ( "群组" );
        case 3:
            return L ( "部门" );
        case 4:
            return L ( "区域模块" );
        case 5:
            return L ( "产品模块" );
        case 6:
            return L ( "日志" );
        case 7:
            return L ( "登录" );
        case 8 :
            return L ( "公告模块" );
        case 0:
            return L ( "异常消息" );
        default :
            return L ( "未定义模块" );
    }
}

function an_status ( $falg )
{
    switch ( $falg )
    {
        case 1:
            return L ( "已发布" );
        case 0:
            return L ( "草稿" );
    }
}

function mod_area_name ( $json )
{
    require_once '../shell/class/dao/area.class.php';
    $data['am_id'] = $json;
    $area = new area ( $data );
    $result = json_decode ( $area->getbyjson () );
    return implode ( ' ' , $result );
}

function scriptmodule ( $m )
{
    return <<<EOC
<script src="?m=loader&a=s&do=$m"></script>
EOC;
}

function scriptafter ( $src )
{
    return <<<EOC
<script src="?m=loader&a=s&do=after&p={$src}"></script>
EOC;
}

function script ( $src )
{
    return <<<EOC
<script src="?m=loader&a=s&p={$src}"></script>
EOC;
}

function scriptnocompile ( $src )
{
    return <<<EOC
<script src="?m=loader&nocompile=true&a=s&p={$src}"></script>
EOC;
}

function style ( $src )
{
    return <<<EOC
<link href="?m=loader&a=c&p={$src}" rel="stylesheet" type="text/css" />
EOC;
}

/**
 * 获取字符串长度，字符串，长度，如果超过长度显示的...
 */
function mbsubstr ( $str , $length = 10 , $view = '...' )
{

    $s = mb_substr ( $str , 0 , $length );
    if ( mb_strlen ( $str ) > $length )
    {
        $s .= $view;
    }
    return $s;
}

/**
 * 用户类型
 * @param type $str
 * @return string
 */
function modwordtype ( $str )
{
    switch ( $str )
    {
        case "1":
            return "<img src='images/pic_02.png' / > ";
        case "2":
            return "<img src='images/pic_03.png' / > ";
        case "3":
            return "<img src='images/pic_04.png' / > ";
        case "4":
            return "<img src='images/pic_01.png' / > ";
        default :
            return "未知";
    }
}
/**
 * 用户状态
 * @param type $str
 * @return string
 */
function modwordstatus ( $str )
{
    switch ( $str )
    {
        case "1":
            return "<img src='images/pic_07.png' / > ";
        case "2":
            return "<img src='images/pic_08.png' / > ";
        case "3":
            return "<img src='images/pic_09.png' / > ";
        default :
            return "未知";
    }
}
/**
 * 时间预警
 * @param type $str
 * @return string
 */
function modwordprewarning ( $str )
{
    switch ( $str )
    {
        case "1":
            return "<img src='images/pic_10.png' / > ";
        case "2":
            return "<img src='images/pic_11.png' / > ";
        case "3":
            return "<img src='images/pic_12.png' / > ";
        default :
            return "未知";
    }
}
/**
 * 时间预警
 * @param type $str
 * @return string
 */
function modwordpreTraffic ( $str )
{
    switch ( $str )
    {
        case "1":
            return "<img src='images/pic_13.png' / > ";
        case "2":
            return "<img src='images/pic_14.png' / > ";
        case "3":
            return "<img src='images/pic_15.png' / > ";
        default :
            return "未知";
    }
}
/**
 * @param $imei 用户所填IMEI
 * @param $u_e_id 用户所属代理商企业ID
 * @return bool|string TRUE->该终端可用于绑定|FALSE->终端不存在库中 不能绑定|Binding->终端已被绑定|Not Belong->不属于该用户所属代理商 不能绑定
 * @throws Exception
 */
function check_md_imei($imei,$u_e_id){
        $term=new terminal(array("md_imei"=>$imei));
        $ep=new enterprise(array("e_id"=>$u_e_id));
        $res=$term->getselect_list();
        $res_ep=$ep->getByid();
        if($imei!=""){
                if($res){//是否在库中?
                    if($res['md_parent_ag']=="0"){
                        $condition=$res_ep['e_ag_path']==="|".$res['md_parent_ag']."|";
                    }else{
                        $condition=strpos($res_ep['e_ag_path'],"|".$res['md_parent_ag']."|")!==false || $res_ep['e_ag_path']==="|".$res['md_parent_ag']."|";
                    }
                    if($condition){//是否属于该用户所属代理商或OMP
                                if($res['md_binding']===0){//是否绑定? 0 未绑定
                                        $info['res']=TRUE;
                                        $info['md_type']=$res['md_type'];
                                        return $info;
                                }else{
                                        return "Binding";//已经绑定
                                }
                        }else{
                                return "Not Belong";//不属于该用户所属代理商
                        }
                }else{
                        return "Not in the library";//不存在库中 即 in the user
                }
        }else{
                return "isnull";
        }

}

/**
 * @param $meid 用户所填MEID
 * @param $u_e_id 用户所属代理商企业ID
 * @return bool|string TRUE->该终端可用于绑定|FALSE->终端不存在库中 不能绑定|Binding->终端已被绑定|Not Belong->不属于该用户所属代理商 不能绑定
 * @throws Exception
 */
function check_md_meid($meid,$u_e_id){
    $term=new terminal(array("md_meid"=>$meid));
    $ep=new enterprise(array("e_id"=>$u_e_id));
    $res=$term->checkexcel_meid($meid);
    $res_ep=$ep->getByid();
    if($meid!=""){
        if($res){//是否在库中?
            if($res['md_parent_ag']=="0"){
                $condition=$res_ep['e_ag_path']==="|".$res['md_parent_ag']."|";
            }else{
                $condition=strpos($res_ep['e_ag_path'],"|".$res['md_parent_ag']."|")!==false || $res_ep['e_ag_path']==="|".$res['md_parent_ag']."|";
            }
            if($condition){//是否属于该用户所属代理商或OMP
            	if($res['md_binding']===0){//是否绑定? 0 未绑定
                    $info['res']=TRUE;
                    $info['md_type']=$res['md_type'];
                    return $info;
                }else{
                    return "Binding";//已经绑定
                }
            }else{
                return "Not Belong";//不属于该用户所属代理商
            }
        }else{
            return "Not in the library";//不存在库中 即 in the user
        }
    }else{
        return "isnull";
    }
}