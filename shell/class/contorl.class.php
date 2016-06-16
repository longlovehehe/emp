<?php

class contorl {

	public $smarty;
	public $tools;
	public $title;
	public $content;
	public $page;

	function __construct() {
		$this->smarty = new smartyex();
		$this->tools = new tools();
	}

	public function permissions($own, $admin = false) {
		if ($own["em_id"] == "") {
			$msg = L("尚未登录系统，无法访问");
			$this->smarty->assign('msg', $msg);
			$this->smarty->display('modules/system/login.tpl');
			exit();
		}
		$_REQUEST['e_id'] = $own['em_ent_id'];
	}

	function content($content) {
		//$content = preg_replace("/\s+/", " ", $content); //过滤多余回车
		//$content = preg_replace("/<[ ]+/si", "<", $content); //过滤<__("<"号后面带空格)
		//$content = preg_replace("/<\!--.*?-->/si", "", $content); //注释

		$this->content = $content;
	}

	function htmlrender($tpl, $flag = false) {
		$this->content($this->smarty->fetch($tpl));
		if ($flag) {
			return $this->content;
		}
		$this->smarty->assign('header', $this->content);
		$this->smarty->display('_cache.tpl');
	}

	function render($tpl, $title = "", $script = array(), $style = array()) {
		$this->title = $title;
		$this->smarty->assign('title', $this->title);
		$this->content($this->smarty->fetch($tpl));
		$tplurl = str_replace('/', '__', $tpl);

		$this->smarty->assign('content', $this->content);
		$this->smarty->assign('data', $_REQUEST);

		$script = array_merge(
			$script
			, array("{$tplurl}")
		);

		$style = array_merge(
			array(
				'reset'
				, 'jquery-ui'
				, 'common'
				, 'skin'
				, 'pic.min'
				, 'limit',
			)
			, $style
			, array('color')
			, array('red.color')
			, array("{$tplurl}")
		);
		$config =parse_ini_file(ROOT_ADDR."/private/config/config.ini", true);
		$script = implode('|', $script);
		$style = implode('|', $style);
		$this->smarty->assign('script', $script);
		$this->smarty->assign('style', $style);
		$this->smarty->assign ( 'url' , $config['gdspage']['url'] );
		$this->smarty->display('_layout.tpl');
	}

}
