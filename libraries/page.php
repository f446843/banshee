<?php
	/* libraries/page.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 *
	 * Don't change this file, unless you know what you are doing.
	 */

	final class page {
		private $db = null;
		private $settings = null;
		private $user = null;
		private $module = null;
		private $url = null;
		private $page = null;
		private $type = "";
		private $http_code = 200;
		private $is_public = true;
		private $pathinfo = array();
		private $parameters = array();
		private $ajax_request = false;
		private $write_access = true;

		/* Constructor
		 *
		 * INPUT:  object database, object settings, object user
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($db, $settings, $user) {
			$this->db = $db;
			$this->settings = $settings;
			$this->user = $user;

			/* AJAX request
			 */
			if (($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") || ($_GET["output"] == "ajax")) {
				$this->ajax_request = true;
			}

			/* Select module
			 */
			if (is_false(WEBSITE_ONLINE) && ($_SERVER["REMOTE_ADDR"] != WEBSITE_ONLINE)) {
				$page = "offline";
			} else if ($this->db->connected == false) {
				$this->module = ERROR_MODULE;
				$this->http_code = 500;
			} else {
				list($this->url) = explode("?", $_SERVER["REQUEST_URI"], 2);
				$path = trim($this->url, "/");
				if ($path == "") {
					$page = $this->settings->start_page;
				} else if (valid_input($path, VALIDATE_URL, VALIDATE_NONEMPTY)) {
					$page = $path;
				} else {
					$this->module = ERROR_MODULE;
					$this->http_code = 404;
				}
				$this->pathinfo = explode("/", $page);
			}

			if ($this->module === null) {
				$this->select_module($page);
			}

			/* Write access
			 */
			if (in_array($this->module, private_rorw_pages())) {
				$query = "select count(*) as count from users u, user_role l, roles r ".
						 "where u.id=%d and u.id=l.user_id and l.role_id=r.id and %S=%d";
				if (($result = $db->execute($query, $this->user->id, $this->module, ACCESS_YES)) !== false) {
					if ($result[0]["count"] == 0) {
						$this->write_access = false;
					}
				}
			}
		}

		/* Desctructor
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __destruct() {
			$_SESSION["previous_module"] = $this->module;
			$_SESSION["last_visit"] = time();
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			switch ($key) {
				case "module": return $this->module;
				case "url": return $this->url;
				case "page": return $this->page !== null ? $this->page : $this->module;
				case "type": return ltrim($this->type, ".");
				case "view": return $this->module.$this->type;
				case "pathinfo": return $this->pathinfo;
				case "parameters": return $this->parameters;
				case "http_code": return $this->http_code;
				case "ajax_request": return $this->ajax_request;
				case "write_access": return $this->write_access;
				case "is_public": return $this->is_public;
				case "is_private": return $this->is_public == false;
			}

			return null;
		}

		/* Page available on disk
		 *
		 * INPUT:  string URL, string page configuration file
		 * OUTPUT: string module identifier
		 * ERROR:  null
		 */
		private function page_on_disk($url, $pages) {
			$module = null;
			$url = explode("/", $url);
			$url_count = count($url);

			foreach ($pages as $line) {
				$page = explode("/", $line);
				$parts = count($page);
				$match = true;

				for ($i = 0; $i < $parts; $i++) {
					if ($page[$i] == "*") {
						continue;
					} else if ($page[$i] !== $url[$i]) {
						$match = false;
						break;
					}
				}

				if ($match && (strlen($line) >= strlen($module))) {
					$module = page_to_module($line);
					$this->type = page_to_type($line);
				}
			}

			return $module;
		}

		/* Page available in database
		 *
		 * INPUT:  string URL, int private page
		 * OUTPUT: string module identifier
		 * ERROR:  null
		 */
		private function page_in_database($url, $private) {
			$query = "select id,visible from pages where url=%s and private=%d limit 1";
			if (($result = $this->db->execute($query, "/".$url, $private)) == false) {
				return null;
			}

			if ($result[0]["visible"] == NO) {
				if ($this->user->access_allowed("admin/page") == false) {
					return null;
				}
			}
			$this->url = "/".$url;
			$this->page = $url;

			return PAGE_MODULE;
		}

		/* Determine what module needs te be loaded based on requested page
		 *
		 * INPUT:  string page identifier
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function select_module($page) {
			if (($this->module !== null) && ($this->module !== LOGIN_MODULE)) {
				return;
			}

			/* Old browser
			 */
			if (preg_match("/MSIE [567]/", $_SERVER["HTTP_USER_AGENT"]) > 0) {
				$this->module = "banshee/browser";
				return;
			}

			/* Public page
			 */
			if (($this->module = $this->page_on_disk($page, public_pages())) !== null) {
				$module_count = substr_count($this->module, "/") + 1;
				$this->parameters = array_slice($this->pathinfo, $module_count);
				return;
			} else if (($this->module = $this->page_in_database($page, NO)) !== null) {
				return;
			}

			/* Change profile before access to private pages
			 */
			if ($this->user->logged_in && ($page != LOGOUT_MODULE)) {
				if (($this->user->status == USER_STATUS_CHANGEPWD) && (isset($_SESSION["user_switch"]) == false)) {
					$page = "profile";
					$this->type = "";
				}
			}

			/* Private page
			 */
			if (($this->module = $this->page_on_disk($page, private_pages())) === null) {
				$this->module = $this->page_in_database($page, YES);
			}

			if ($this->module == null) {
				/* Page does not exist.
				 */
				$this->module = ERROR_MODULE;
				$this->http_code = 404;
				$this->type = "";
			} else if ($this->user->logged_in == false) {
				/* User not logged in.
				 */
				$this->module = LOGIN_MODULE;
				$this->type = "";
			} else if ($this->user->access_allowed($this->__get("page").$this->type) == false) {
				/* Access denied because not with right role.
				 */
				$this->module = ERROR_MODULE;
				$this->http_code = 403;
				$this->type = "";
				$this->user->log_action("unauthorized request for page %s", $page);
			} else {
				/* Access allowed.
				 */
				$this->is_public = false;
				$_SESSION["last_private_visit"] = time();

				$module_count = substr_count($this->module, "/") + 1;
				$this->parameters = array_slice($this->pathinfo, $module_count);
			}
		}
	}
?>
