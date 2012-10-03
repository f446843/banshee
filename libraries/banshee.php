<?php
	/* libraries/banshee.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	/* For internal usage. Only change if you know what you're doing!
	 */
	define("BANSHEE_VERSION", "3.6");
	define("ADMIN_ROLE_ID", 1);
	define("YES", 1);
	define("NO", 0);
	define("USER_STATUS_DISABLED", 0);
	define("USER_STATUS_CHANGEPWD", 1);
	define("USER_STATUS_ACTIVE", 2);
	define("ACCESS_NO", 0);
	define("ACCESS_YES", 1);
	define("ACCESS_READONLY", 2);
	define("PASSWORD_HASH", "sha256");
	define("SESSION_NAME", "WebsiteSessionID");
	define("DAY", 86400);
	define("PAGE_MODULE", "system/page");
	define("ERROR_MODULE", "system/error");
	define("LOGIN_MODULE", "login");
	define("LOGOUT_MODULE", "logout");
	define("FPDF_FONT_PATH", "../extra/fpdf_fonts/");
	define("PHOTO_PATH", "photos");

	/* Auto class loader
	 *
	 * INPUT:  string class name
	 * OUTPUT: -
	 * ERROR:  -
	 */
	function __autoload($class_name) {
		$rename = array(
			"https"               => "http",
			"jpeg_image"          => "image",
			"png_image"           => "image",
			"gif_image"           => "image",
			"pop3s"               => "pop3",
			"banshee_website_ssl" => "banshee_website");

		$class_name = strtolower($class_name);
		if (isset($rename[$class_name])) {
			$class_name = $rename[$class_name];
		}

		$locations = array("libraries", "libraries/database");
		foreach ($locations as $location) {
			if (file_exists($file = "../".$location."/".$class_name.".php")) {
				include_once($file);
				break;
			}
		}
	}

	/* Convert mixed to boolean
	 *
	 * INPUT:  mixed
	 * OUTPUT: boolean
	 * ERROR:  -
	 */
	function is_true($bool) {
		if (is_string($bool)) {
			$bool = strtolower($bool);
		}

		return in_array($bool, array(true, YES, "1", "yes", "true", "on"), true);
	}

	/* Convert mixed to boolean
	 *
	 * INPUT:  mixed
	 * OUTPUT: boolean
	 * ERROR:  -
	 */
	function is_false($bool) {
		return (is_true($bool) === false);
	}

	/* Convert boolean to string
	 *
	 * INPUT:  boolean
	 * OUTPUT: string "yes"|"no"
	 * ERROR:  -
	 */
	function show_boolean($bool) {
		return (is_true($bool) ? "yes" : "no");
	}

	/* Return all public pages
	 *
	 * INPUT:  -
	 * OUTPUT: array public pages
	 * ERROR:  -
	 */
	function public_pages() {
		return config_file("public_pages");
	}

	/* Return all private pages
	 *
	 * INPUT:  -
	 * OUTPUT: array private pages
	 * ERROR:  -
	 */
	function private_pages() {
		$config = config_file("private_pages");

		$pages = array();
		foreach ($config as $line) {
			list($page) = explode(":", $line, 2);
			array_push($pages, $page);
		}

		return $pages;
	}

	/* Return all pages with OPtional ReadOnly Access Rights
	 *
	 * INPUT:  -
	 * OUTPUT: array OPROAR pages
	 * ERROR:  -
	 */
	function oproar_pages() {
		$config = config_file("private_pages");

		$pages = array();
		foreach ($config as $line) {
			list($page, $type) = explode(":", $line, 2);
			if ($type == "readonly") {
				array_push($pages, $page);
			}
		}

		return $pages;
	}

	/* Convert a page path to a module path
	 *
	 * INPUT:  array / string page path
	 * OUTPUT: array / string module path
	 * ERROR:  -
	 */
	function page_to_module($page) {
		if (is_array($page) == false) {
			$page = str_replace("*/", "", $page);

			if (($pos = strrpos($page, ".")) !== false) {
				$page = substr($page, 0, $pos);
			}
		} else foreach ($page as &$item) {
			$item = page_to_module($item);
		}

		return $page;
	}

	/* Convert a page path to a page type
	 *
	 * INPUT:  array / string page path
	 * OUTPUT: array / string page type
	 * ERROR:  -
	 */
	function page_to_type($page) {
		if (is_array($page) == false) {
			if (($pos = strrpos($page, ".")) !== false) {
				$page = substr($page, $pos);
			} else {
				$page = "";
			}
		} else foreach ($page as &$item) {
			$item = page_to_type($item);
		}

		return $page;
	}

	/* Check for module existence
	 *
	 * INPUT:  string module
	 * OUTPUT: bool module exists
	 * ERROR:  -
	 */
	function module_exists($module) {
		if (in_array($module, public_pages())) {
			return true;
		} else if (in_array($module, private_pages())) {
			return true;
		}

		return false;
	}

	/* Check for library existence
	 *
	 * INPUT:  string library
	 * OUTPUT: bool library exists
	 * ERROR:  -
	 */
	function library_exists($library) {
		return file_exists("../libraries/".$library.".php");
	}

	/* Log debug information
	 *
	 * INPUT:  string format[, mixed arg...]
	 * OUTPUT: true
	 * ERROR:  false
	 */
	function debug_log($info) {
		if (func_num_args() > 1) {
			$args = func_get_args();
			array_shift($args);
			$info = vsprintf($action, $args);
		} else if (is_array($info)) {
			foreach ($info as $key => &$value) {
				$value = "\t".$key." => ".chop($value);
			}
			$info = "array:\n".implode("\n", $info);
		}

		if (($fp = fopen("../logfiles/debug.log", "a")) == false) {
			return false;
		}

		fputs($fp, sprintf("%s|%s|%s|%s\n", $_SERVER["REMOTE_ADDR"], date("D d M Y H:i:s"), $_SERVER["REQUEST_URI"], $info));
		fclose($fp);

		return true;
	}

	/* Flatten array to new array with depth 1
	 *
	 * INPUT:  array
	 * OUTPUT: array
	 * ERROR:  -
	 */
	function array_flatten($data) {
		$result = array();
		foreach ($data as $item) {
			if (is_array($item)) {
				$result = array_merge($result, array_flatten($item));
			} else {
				array_push($result, $item);
			}
		}

		return $result;
	}

	/* Localized date string
	 *
	 * INPUT:  string format[, integer timestamp]
	 * OUTPUT: string date
	 * ERROR:  -
	 */
	function date_string($format, $timestamp = null) {
		global $days_of_week, $months_of_year;

		if ($timestamp === null) {
			$timestamp = time();
		}

		$format = strtr($format, "lDFM", "#$%&");
		$result = date($format, $timestamp);

		$day = $days_of_week[(int)date("N", $timestamp) - 1];
		$result = str_replace("#", $day, $result);

		$day = substr($days_of_week[(int)date("N", $timestamp) - 1], 0, 3);
		$result = str_replace("$", $day, $result);

		$month = $months_of_year[(int)date("n", $timestamp) - 1];
		$result = str_replace("%", $month, $result);

		$month = substr($months_of_year[(int)date("n", $timestamp) - 1], 0, 3);
		$result = str_replace("&", $month, $result);

		return $result;
	}

	/* Decode a GZip encoded string
	 *
	 * INPUT:  string GZip data
	 * OUTPUT: string data
	 * ERROR:  -
	 */
	if (function_exists("gzdecode") == false) {

	function gzdecode($data) {
		$file = tempnam("/tmp", "gzip");

		@file_put_contents($file, $data);
		ob_start();
		readgzfile($file);
		$data = ob_get_clean();
		unlink($file);

		return $data;
	}

	}

	/* Load configuration file
	 *
	 * INPUT:  string configuration
	 * OUTPUT: array( key => value[, ...] )
	 * ERROR:  -
	 */
	function config_file($file) {
		static $cache = array();

		if (isset($cache[$file])) {
			return $cache[$file];
		}

		$config_file = "../settings/".$file.".conf";
		if (file_exists($config_file) == false) {
			return array();
		}

		$config = array();
		foreach (file($config_file) as $line) {
			if (($line = trim(preg_replace("/#.*/", "", $line))) !== "") {
				array_push($config, $line);
			}
		}

		$cache[$file] = $config;

		return $config;
	}

	/* Parse website.conf
	 */
	foreach (config_file("website") as $line) {
		list($key, $value) = explode("=", chop($line), 2);
		define(trim($key), trim($value));
	}

	/* PHP settings
	 */
	ini_set("magic_quotes_runtime", 0);
?>
