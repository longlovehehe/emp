<?php

/*
 * require_once '../shell/class/contorl.class.php';
 */

require_once '../private/libs/Minify/JS/jsconfusion.php';
require_once '../private/libs/Minify/CSS/Compressor.php';

class LoaderContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
        $this->smarty = new smartyex();
        $this->smarty->template_dir = "../static";
        $this->smarty->left_delimiter = '<%';
        $this->smarty->right_delimiter = '%>';
        $config = $this->tools->getconfig ();
        if ( $config['config']['cache'] )
        {
            $this->smarty->caching = TRUE;
            $this->smarty->cache_lifetime = 31 * 60 * 60 * 24;
            $this->smarty->force_compile = FALSE;
        }
        else
        {
            $this->smarty->caching = FALSE;
            $this->smarty->cache_lifetime = 0;
            $this->smarty->force_compile = TRUE;
        }
    }

    public function script ()
    {
        $config = $this->tools->getconfig ();
        if ( $_REQUEST['p'] != '' )
        {
            $p = explode ( '|' , $_REQUEST['p'] );
        }
        if ( $_REQUEST['do'] == 'login' )
        {
            $p = array_merge ( array (
                'libs/jquery-1.11.1.min'
                , 'libs/jquery.cookie'
                , 'layer/layer'
                , 'login'
            ) );
        }
        if ( $_REQUEST['do'] == 'before' )
        {
            $lang = $_COOKIE['lang'];
             if($lang=="en_US"){
                $file_js1='libs/jquery-validation/localization/messages.en.min';
                $file_js2='libs/jquery-ui-1.11.1/i18n/jquery.ui.datepicker-en-US';
            }else if($lang=="zh_TW"){
                $file_js1='libs/jquery-validation/localization/messages.zh.TW.min';
                $file_js2='libs/jquery-ui-1.11.1/i18n/jquery.ui.datepicker-zh-TW';
            }else{
                $file_js1='libs/jquery-validation/localization/messages.zh.min';
                $file_js2='libs/jquery-ui-1.11.1/i18n/jquery.ui.datepicker-zh-CN';
            }
            $p = array_merge (
                    array (
                        'lib.sizzle'
                        , 'libs/jquery-1.11.1.min'
                        , 'libs/jquery-ui-1.11.1/jquery-ui'
                        , 'libs/jquery.cookie'
                        , 'libs/jquery-validation/jquery.validate.min'
                        ,$file_js1
                        , $file_js2
                        , 'libs/nicEdit.min'
                        , 'layer/layer'
                        , 'jquery.validate.additional'
                        , 'libs/jquery-ui-timepicker-addon'
                        , 'lib.rain.0.1.2.min'
                        , 'before'
                        , 'iPass.packed'
                    )
            );
        }
        if ( $_REQUEST['do'] == 'after' )
        {
            $p = array_merge (
                    array (
                'lang'
                , 'common'
                , 'after'
                    )
                    , $p
            );
        }


        $content = '';
        foreach ( $p as $value )
        {
            try
            {
                $content .= PHP_EOL . "'add: script/{$value}.js';" . PHP_EOL;
                $tmp = $this->smarty->fetch ( 'script/' . str_replace ( '__' , '/' , $value ) . '.js' );
                $content .= $tmp;
            }
            catch ( Exception $ex )
            {
                if ( $ex->getCode () === 0 )
                {
                    $path = '../static/script/' . str_replace ( '__' , '/' , $value ) . '.js';
                    $pathdir = explode ( '/' , $path );
                    array_pop ( $pathdir );
                    if ( ! file_exists ( $path ) )
                    {
                        $pathdir = implode ( '/' , $pathdir );
                        mkdir_r ( $pathdir );
                        file_put_contents ( $path , '/* 此文件由资源管理器自动创建生成。该文件用于开发时，可以选择删除这条注释 */' . PHP_EOL , FILE_APPEND );
                    }
                }
                $content .= PHP_EOL . "'Exception: script/{$value}.js{$ex->getCode ()}';" . PHP_EOL;
            }
        }
        if ( $config['config']['compile'] && $_REQUEST['nocompile'] != 'true' )
        {
            $myPacker = new JavaScriptPacker ( $content , 62 , true , false );
            $content = $myPacker->pack ();
        }

        $this->smarty->assign ( 'cache' , $content );
    }

    //@script
    public function s ()
    {
        header ( 'Content-type: text/javascript;charset:"UTF8"' );
        $mask = md5 ( 'script' . json_encode ( $_REQUEST ) );

        if ( ! $this->smarty->isCached ( '_cache.tpl' , $mask ) )
        {
            $this->script ();
        }

        $this->smarty->display ( '_cache.tpl' , $mask );
    }

    //@css
    public function c ()
    {
        $p = explode ( '|' , $_REQUEST['p'] );
        $mask = md5 ( 'style' . $_REQUEST['p'] );
        header ( 'Content-type: text/css;charset:"UTF8"' );
        print('@charset "UTF-8";' . PHP_EOL );
        if ( ! $this->smarty->isCached ( '_cache.tpl' , $mask ) )
        {
            $content = '';
            foreach ( $p as $value )
            {
                try
                {
                    $content .= "/*add: style/{$value}.css;*/";
                    $tmp = $this->smarty->fetch ( 'style/' . str_replace ( '__' , '/' , $value ) . '.css' );
                    $content .= $tmp;
                }
                catch ( Exception $ex )
                {
                    if ( $ex->getCode () == 0 )
                    {
                        $path = '../static/style/' . str_replace ( '__' , '/' , $value ) . '.css';
                        $pathdir = explode ( '/' , $path );
                        array_pop ( $pathdir );

                        if ( ! file_exists ( $path ) )
                        {
                            $pathdir = implode ( '/' , $pathdir );

                            mkdir_r ( $pathdir );
                            file_put_contents ( $path , '' , FILE_APPEND );
                        }
                    }
                    $content .= "/*Exception:style/{$value}.css{$ex->getCode ()}*/;" . PHP_EOL;
                }
            }

            $content = Minify_CSS_Compressor::process ( $content );

            $this->smarty->assign ( 'cache' , $content );
        }
        $this->smarty->display ( '_cache.tpl' , $mask );
    }

}
