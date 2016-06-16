<?php
    header("Content-type:text/html;charset=utf-8");
    $code = trim($_GET['code']);
    $msg = trim($_GET['msg']);
    switch ($msg) {
        case '1':
            echo "未获取到ticket";
            break;
        case '2':
            echo "请求鉴权返回错误";
            break;
        case '3':
            echo "企业id(EcID)错误";
            break;
        case '4':
            echo "密码错误";
            break;
        case '5':
            echo "权限不够";
            break;
        case '6':
            echo "请求鉴权返回失败,错误代码：".$code;
            break;
        case '7':
            echo "鉴权返回数据为空或未返回数据";
            break;
        default:
            echo "未知错误";
            break;
    }
?>
