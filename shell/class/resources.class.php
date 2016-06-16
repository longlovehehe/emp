<?php

/**
 * 资源管理器
 * @deprecated since version number
 */
class resources
{

    public $m;
    public $a;
    public $key;
    public $styleMask;
    public $stylePath;
    public $styleCachePath;
    public $scriptMask;
    public $scriptLibsMask;
    public $scriptPath;
    public $scriptCachePath;
    public $scriptLibsCachePath;
    public $map;
    public $webPath;

    public function __construct ()
    {

    }

    public function set ( $key , $ver , $debug = false )
    {
        $this->key = "modules.{$key}";
        $this->mask = md5 ( $this->key . $ver );
        $this->styleMask = md5 ( 'style|' . $this->map['style']['common'] . ',' . $this->map['style'][$this->key] . ',' . $this->key );
        $this->stylePath = WEBROOT
                . DIRECTORY_SEPARATOR . 'static'
                . DIRECTORY_SEPARATOR . 'style'
                . DIRECTORY_SEPARATOR;
        $this->styleCachePath = WEBROOT
                . DIRECTORY_SEPARATOR . 'runtime'
                . DIRECTORY_SEPARATOR . 'cache'
                . DIRECTORY_SEPARATOR . $this->styleMask . '.css';

        $this->scriptMask = md5 ( 'script|' . $this->key . $ver );
        $this->scriptLibsMask = md5 ( 'script|' . $this->map['script']['common'] . ',' . $this->map['script'][$this->key] . $ver );

        $this->scriptPath = WEBROOT
                . DIRECTORY_SEPARATOR . 'static'
                . DIRECTORY_SEPARATOR . 'script'
                . DIRECTORY_SEPARATOR;
        $this->scriptCachePath = WEBROOT
                . DIRECTORY_SEPARATOR . 'runtime'
                . DIRECTORY_SEPARATOR . 'cache'
                . DIRECTORY_SEPARATOR . $this->scriptMask . '.js';
        $this->scriptLibsCachePath = WEBROOT
                . DIRECTORY_SEPARATOR . 'runtime'
                . DIRECTORY_SEPARATOR . 'cache'
                . DIRECTORY_SEPARATOR . $this->scriptLibsMask . '.js';

        $this->map = json_decode ( file_get_contents ( WEBROOT . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'map.json' ) , TRUE );
        if ( $debug )
        {
            $this->create ( 'script' , 'js' );
            $this->create ( 'style' , 'css' );
        }
    }

    public function create ( $type , $suffix )
    {
        if ( ! isset ( $this->map[$type][$this->key] ) )
        {
            $this->map[$type][$this->key] = '';
            file_put_contents ( WEBROOT . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'map.json' , json_encode ( $this->map ) );
            file_put_contents ( WEBROOT . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $this->key . ".{$suffix}" , '' , FILE_APPEND );
            $this->map[$type][$this->key];
        }
    }

    public function style ()
    {
        return $this->styleMask;
    }

    public function stylemerge ()
    {
        file_put_contents ( $this->styleCachePath , '@charset "UTF-8";' . PHP_EOL );
        $style = $this->map['style']['common'] . ',' . $this->map['style'][$this->key] . ',' . $this->key;
        $style = explode ( ',' , $style );


        foreach ( $style as $value )
        {
            if ( $value != '' )
            {
                $content = <<<NOTE
/*===============================
KEY:{$value}
===============================*/
NOTE;
                $content .= PHP_EOL . file_get_contents ( $this->stylePath . $value . '.css' ) . PHP_EOL;

                file_put_contents ( $this->styleCachePath
                        , $content
                        , FILE_APPEND );
            }
        }
    }

    public function script ()
    {
        return $this->scriptMask;
    }

    public function scriptlibs ()
    {
        return $this->scriptLibsMask;
    }

    public function scriptmerge ()
    {
        file_put_contents ( $this->scriptCachePath , '' );
        $script = $this->map['script']['common'] . ',' . $this->map['script'][$this->key];
        $script = explode ( ',' , $script );

        foreach ( $script as $value )
        {
            if ( $value != '' )
            {
                $content = <<<NOTE
/*===============================
KEY:{$value}
===============================*/
NOTE;
                $content .= PHP_EOL . file_get_contents ( $this->scriptPath . $value . '.js' ) . PHP_EOL;

                file_put_contents ( $this->scriptCachePath
                        , $content
                        , FILE_APPEND );
            }
        }
    }

}
