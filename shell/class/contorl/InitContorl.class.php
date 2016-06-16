<?php

/**
 * 初始化类
 * @category EMP
 * @package EMP_Common
 * @require {@see contorl};
 */
class InitContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

     /**
     * 初始化函数，检查private/config/db.json文件是否存在，不存在则，输出初始化页面
     */
    public function init_lang ()
    {
        /*
          if (!file_exists('../private/config/db.json'))
          {
          $_REQUEST['date'] = time();
          $this->htmlrender('_init.tpl');
          }
         */
        //选择语言
        if ( ! file_exists ( '../private/config/db.json' ) )
        {
            $_REQUEST['date'] = time ();
            $this->htmlrender ( '_language.tpl' );
        }
    }

    /**
     * 初始化函数，检查private/config/db.json文件是否存在，不存在则，输出初始化页面
     */
    public function init ()
    {
        if ( ! file_exists ( '../private/config/db.json' ) )
        {
            $_REQUEST['date'] = time ();
            $this->htmlrender ( '_init.tpl' );
        }
    }

    /**
     * 初始化过程中的提示信息刷新
     * @param type $str
     * @param type $flag
     * @return type
     */
    public function info ( $str , $flag = FALSE )
    {
        //sleep ( 1 );
        $script = '<h2 class="none">%s</h2>';
        $script = sprintf ( $script , $str );
        $script .= '<script>$("h2.show").remove();$("h2.none").removeClass("none").addClass("show");</script>';
        if ( $flag )
        {
            return $script;
        }
        echo $script;
        ob_flush ();
        flush ();
    }

    /**
     * 校验初始化过程中提交的数据库端口，名称，用户，密码，模式等必填项是否都填写了。未填写抛出对应异常
     * @throws Exception
     */
    public function vaild ()
    {
        if ( $_REQUEST['dbport'] == "" )
        {
            throw new Exception ( 'dbport is null' , -1 );
        }
        if ( $_REQUEST['dbname'] == "" )
        {
            throw new Exception ( 'dbname is null' , -1 );
        }
        if ( $_REQUEST['dbuser'] == "" )
        {
            throw new Exception ( 'dbuser is null' , -1 );
        }
        if ( $_REQUEST['dbpwd'] == "" )
        {
            throw new Exception ( 'dbpwd is null' , -1 );
        }
        if ( $_REQUEST['debug'] == "" )
        {
            throw new Exception ( 'debug is null' , -1 );
        }
    }

    /**
     * 写入配置文件
     * @param type $path 文件路径
     * @param type $content 文件内容
     */
    public function write ( $path , $content )
    {
        $file = fopen ( $path , 'w' );
        $this->info ( '打开了文件 文件句柄 ' . $file );

        if ( fwrite ( $file , $content ) )
        {
            $this->info ( '写入文件 成功' );
        }
        else
        {
            $this->info ( '写入文件 失败' );
        }
        if ( fflush ( $file ) )
        {
            $this->info ( '强制刷新文件 fflush 成功' );
        }
        else
        {
            $this->info ( '强制刷新文件 fflush 失败' );
        }
        if ( fclose ( $file ) )
        {
            $this->info ( '关闭文件句柄 成功' );
        }
        else
        {
            $this->info ( '关闭文件句柄 失败' );
        }
    }

    /**
     * 创建INI文件
     */
    public function creatINI ()
    {
        $this->info ( '正在校验各配置项' );
        $this->vaild ();
        $ini = array ();
        $ini['data_base']['db_host'] = $_REQUEST['dbhost'];
        $ini['data_base']['db_port'] = $_REQUEST['dbport'];
        $ini['data_base']['db_name'] = $_REQUEST['dbname'];
        $ini['data_base']['db_user'] = $_REQUEST['dbuser'];
        $ini['data_base']['db_pwd'] = $_REQUEST['dbpwd'];
        if ( $_REQUEST['dbtype'] != 'remote' )
        {
            $ini['data_base']['db_host'] = 'localhost';
        }

        $this->info ( '校验完成，正在编码数据' );
        $str = json_encode ( $ini );
        $this->info ( '编码完成，正在写入数据' );
        $this->write ( '../private/config/db.json' , $str );
        $this->info ( '写入完成' );
    }

    /**
     * 初始化SHELL执行窗口
     */
    public function initshell ()
    {
        $doc = <<< DOC
<!DOCTYPE html>
<html>
                <head>
                        <meta charset="UTF-8">
                <script src="?m=loader&amp;a=s&amp;do=before"></script>
                <link href="style/init_shell.css" rel="stylesheet" type="text/css" />
                <head>
                <body>
DOC;
        echo $doc;
        $this->info ( '请稍候。正在准备资源' );

        if ( $_REQUEST['dbtype'] == 'remote' )
        {
            $host = "host=" . $_REQUEST['dbhost'] . ";";
        }

        $url = "pgsql:"
                . $host
                . "port=%port;"
                . "dbname=%dbname;"
                . "user=%username;"
                . "password=%password;";
        $url = str_replace ( '%port' , $_REQUEST['dbport'] , $url );
        $url = str_replace ( '%dbname' , $_REQUEST['dbname'] , $url );
        $url = str_replace ( '%username' , $_REQUEST['dbuser'] , $url );
        $url = str_replace ( '%password' , $_REQUEST['dbpwd'] , $url );

        $this->info ( '正在与数据库进行交互' );
        try
        {
            $pdo = new PDO ( $url );
            $this->info ( '交互中' );
        }
        catch ( Exception $ex )
        {
            $msg = '<h1>' . L ( '与数据库交互失败' ) . '</h1><div class=\'buttons\'><a class=\'login\'>' . L ( '重新填写' ) . '</a></div>';
            $this->info ( $msg );
            $script = <<<S
<script>
                $(".login").click(function(){
                        top.location.href = '?m';
                 });
</script>
S;
            $this->info ( $script );
            exit ();
        }
        $this->info ( L ( '交互成功，存储配置数据' ) );

        try
        {
            $this->creatINI ();
        }
        catch ( Exception $ex )
        {
            $this->info ( "文件创建失败  " . $ex->getCode () . "  " . $ex->getMessage () );
            exit;
        }
        $this->info ( '写入未出现异常' );

        $this->info ( '与数据库建立连接' );
        $pdo = new PDO ( $url );

        $this->info ( '设置客户端连接编码 UTF-8' );
        $pdo->query ( "SET client_encoding = 'UTF-8';" );
        $this->info ( '设置连接方式' );
        $pdo->setAttribute ( PDO::ATTR_EMULATE_PREPARES , false );
        $this->info ( '设置异常抛出模块' );
        $pdo->setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );

        $this->info ( '<h1>' . L ( '开始使用' ) . '</h1><div class=\'buttons \'><a class="login" href=\'?m=login\' target="_parent">' . L ( '立即登录' ) . '</a></div>' );

        $this->info ( $script );
    }

}
