<?php
	/* public/index.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 *
	 * Don't change this file, unless you know what you are doing.
	 */

	ob_start();
	require("../libraries/error.php");
	require("../libraries/banshee.php");
	require("../libraries/security.php");
	require("../settings/configuration.php");

	/* Abort on dangerous PHP settings
	 */
	check_PHP_setting("register_globals", 0);
	check_PHP_setting("allow_url_include", 0);

	/* Undo magic quotes
	 */
	if (ini_get("magic_quotes_gpc") == 1) {
		$superglobals = array(&$_REQUEST, &$_GET, &$_POST, &$_COOKIE);
		foreach ($superglobals as &$superglobal) {
			$superglobal = remove_magic_quotes($superglobal);
		}
	}

	/* Load core modules
	 */
	$_database = new MySQLi_connection(DB_HOSTNAME, DB_DATABASE, DB_USERNAME, DB_PASSWORD);
	$_session  = new session($_database);
	$_settings = new settings($_database);
	$_user     = new user($_database, $_settings, $_session);
	$_page     = new page($_database, $_settings, $_user);
	$_output   = new output($_database, $_settings, $_page);
	if (is_true(MULTILINGUAL)) {
		$_language = new language($_database, $_page, $_output);
	}

	/* Logging
	 */
	if (library_exists("logging") && ($_user->is_admin == false)) {
		$logging = new logging($_database, $_page, $_settings);
		$logging->execute();
	}

	/* Prevent Cross-Site Request Forgery
	 */
	prevent_csrf($_output, $_user);

	/* User switch warning
	 */
	if (isset($_SESSION["user_switch"])) {
		$real_user = $_database->entry("users", $_SESSION["user_switch"]);
		$_output->add_system_warning("User switch active! Switched from '%s' to '%s'.", $real_user["fullname"], $_user->fullname);
	}

	/* Include the model
	 */
	if (file_exists($file = "../models/".$_page->module.".php")) {
		include($file);
	}

	$_output->open_tag("output", array("url" => $_page->url));

	if ($_page->ajax_request == false) {
		$_output->add_tag("banshee_version", BANSHEE_VERSION);
		$_output->add_tag("website_url", $_SERVER["SERVER_NAME"]);

		/* Page information
		 */
		$_output->add_tag("page", $_page->page, array(
			"url"    => $_page->url,
			"module" => $_page->module,
			"type"   => $_page->type));

		/* User information
		 */
		if ($_user->logged_in) {
			$params = array("id" => $_user->id, "admin" => show_boolean($_user->is_admin));
			$_output->add_tag("user", $_user->fullname, $params);
		}

		/* Multilingual
		 */
		if ($_language !== null) {
			$_language->to_output();
		}

		/* Main menu
		 */
		if (is_true(WEBSITE_ONLINE)) {
			if (substr($_page->url, 0, 6) == "/admin") {
				/* CMS menu
				 */
				$_output->open_tag("menu");
				$_output->record(array("link" => "/", "text" => "Website"), "item");
				$_output->record(array("link" => "/admin", "text" => "CMS"), "item");
				$_output->record(array("link" => "/logout", "text" => "Logout"), "item");
				$_output->close_tag();
			} else if ($_output->fetch_from_cache("menu") == false) {
				/* Normal menu
				 */
				$_output->start_caching("menu");
				$menu = new menu($_database, $_output);
				$menu->to_output(0);
				$_output->stop_caching();
			}
		}

		/* Stylesheet
		 */
		$_output->add_css($_page->module.".css");

		$_output->open_tag("content");
	}

	/* Include the controller
	 */
	if (file_exists($file = "../controllers/".$_page->module.".php")) {
		include($file);

		$controller_class = str_replace("/", "_", $_page->module)."_controller";
		if (class_exists($controller_class) == false) {
			print "Controller class '".$controller_class."' does not exist.\n";
		} else if (is_subclass_of($controller_class, "controller") == false) {
			print "Controller class '".$controller_class."' does not extend 'controller'.\n";
		} else {
			$_controller = new $controller_class($_database, $_settings, $_user, $_page, $_output, $_language);
			$method = "execute";

			if (is_true(URL_PARAMETERS)) {
				$reflection = new reflectionobject($_controller);
				$param_count = count($reflection->getmethod($method)->getParameters());
				unset($reflection);

				$params = array_pad($_page->parameters, $param_count, null);
				call_user_func_array(array($_controller, $method), $params);
			} else {
				$_controller->$method();
			}
			unset($_controller);
		}
	}

	if ($_page->ajax_request == false) {
		$_output->close_tag();
	}

	/* Errors
	 */
	$errors = ob_get_clean();

	if ($errors != "") {
		$error_handler = new website_error_handler($_output, $_settings);
		$error_handler->execute($errors);
		unset($error_handler);
	}

	/* Close output
	 */
	ob_start();
	$_output->close_tag();
	ob_end_clean();

	/* Output content
	 */
	$_output->generate();
?>
