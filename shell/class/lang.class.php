<?php

class lang
{

    private $lang;
    private $path;

    public function lang ( $path )
    {
        $this->path = $path . '/template/lang';
    }

    public function getText ()
    {
        $lang = $this->path . '/' . $this->lang . '.ini';
        return parse_ini_file ( $lang , true );
    }

    public function en_US ()
    {
        $this->lang = 'en_US';
    }

}
