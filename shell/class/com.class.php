<?php

/**
 * 通用公共函数库
 * @category EMP
 * @package EMP_Common
 */
class coms {

	private static $config = NULL;
	private static $db = NULL;
	private static $tpl = NULL;
	public static $res = array();
	public static $diff_res = array();

    public static function lang() {
            switch ($_COOKIE['lang']) {
                    case "en_US":
                            coms::$res = parse_ini_file('../static/i18n/en_US.ini', true);
                            break;
                    case "zh_TW":
                            coms::$res = parse_ini_file('../static/i18n/zh_TW.ini', true);
                            break;
                            //default :
                            //  coms::$res = parse_ini_file ( '../static/i18n/zh_CN.ini' , true );
                            // break;
            }
    }
    public static function dlang() {

            switch ($_COOKIE['default_lang']) {
                    case "en_US":
                            coms::$diff_res = parse_ini_file('../static/i18n/en_US.ini', true);
                            break;
                    case "zh_TW":
                            coms::$diff_res = parse_ini_file('../static/i18n/zh_TW.ini', true);
                            break;
                            //default :
                            //  coms::$res = parse_ini_file ( '../static/i18n/zh_CN.ini' , true );
                            // break;
            }
    }

	/**
	 * 统一输出
	 * 参数 json/html/style/script/jpg/png
	 * @param String $str html|reload|json|style|javascript
	 * @todo 补充全部类型支持
	 */
	public static function head($str) {
		switch ($str) {
			case 'html':
				header("Content-type: text/html; charset=utf-8");
				break;
			case 'reload':
				echo "<script>window.location.reload()</script>";
				break;
			case 'script':
				header('Content-type: text/javascript;charset:"UTF8"');
				break;
			case 'json':
				break;
			case 'json':
				break;
			case 'json':
				break;
			case 'json':
				break;
			case 'json':
				break;
		}
	}

	/**
	 *  统一的配置获取接口
	 * @param String $str clear|'' 重新加载配置信息
	 *
	 */
	public static function config($str = '') {
		if ($str === 'clear') {
			coms::$config = NULL;
		}
		if (coms::$config === NULL) {
			coms::$config = parse_ini_file('../private/config/config.ini', true);
		}
		return coms::$config;
	}

	public static function show() {

	}

	/**
	 * 内容日志
	 */
	public static function log($msg, $prefix = "") {
		if ($prefix != "") {
			$prefix .= "_";
		}

		$dir = "../runtime/log/" . Date("Ym") . "/";
		if (!is_dir($dir)) {
			mkdir($dir);
		}

		$path = $dir . $prefix . date("Ymd") . ".log";
		$handle = fopen($path, "a");
		fwrite($handle, date("Y-m-d H:i:s", time()) . "\t" . $_SERVER["REMOTE_ADDR"] . "\t" . $msg . "\n");
		fclose($handle);
		return str_replace('../', '', $path);
	}

	/**
	 * 配置表现一致的数据库接口
	 * @param String $str clear 重新连接数据库
	 */
	public static function db($str = '') {
		if ($str === 'clear') {
			coms::$db = NULL;
		}
		if (coms::$db === NULL) {
			coms::$db = new db();
		}
		return coms::$db;
	}

	/**
	 * 模块引擎
	 */
	public static function tpl() {

	}

}
