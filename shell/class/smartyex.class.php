<?php

require_once ( '../private/libs/Smarty/Smarty.class.php' );

class smartyex extends Smarty
{

    public function __construct ()
    {
        parent::__construct ();
        $this->template_dir = "../template";
        $this->cache_dir = "../runtime/cache";
        $this->compile_dir = "../runtime/template_c";

        $this->cache_dir = "C:\Windows\Temp";
        $this->compile_dir = "C:\Windows\Temp";
        $this->smarty->caching = FALSE;
        $this->smarty->cache_lifetime = 0;
        $this->smarty->force_compile = TRUE;
    }

}
