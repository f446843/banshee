<?php
	/* libraries/menu.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class menu {
		private $db = null;
		private $output = null;
		private $parent_id = 0;
		private $depth = 1;
		private $user = null;

		/* Constructor
		 *
		 * INPUT:  object database, object output
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($db, $output) {
			$this->db = $db;
			$this->output = $output;
		}

		/* Set menu start point
		 *
		 * INPUT:  string link
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function set_start_point($link) {
			$query = "select id from menu where link=%s limit 1";
			if (($menu = $this->db->execute($query, $link)) == false) {
				return false;
			}

			$this->parent_id = (int)$menu[0]["id"];

			return true;
		}

		/* Set menu depth
		 *
		 * INPUT:  int depth
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function set_depth($depth) {
			if (($this->depth = (int)$depth) < 1) {
				$this->depth = 1;
			}
		}

		/* Set user for access check
		 *
		 * INPUT:  object user
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function set_user($user) {
			$this->user = $user;
		}

		/* Get menu data
		 *
		 * INPUT:  int menu identifier[, int menu depth][, string link of active menu item for highlighting]
		 * OUTPUT: array menu data
		 * ERROR:  false
		 */
		private function get_menu($id, $depth = 1, $current_url = null) {
			$query = "select * from menu where parent_id=%d order by %S";
			if (($menu = $this->db->execute($query, $id, "id")) === false) {
				return false;
			}

			$result = array(
				"id"    => $id,
				"items" => array());

			foreach ($menu as $item) {
				$element = array();

				if (($this->user !== null) && ($item["link"][0] == "/")) {
					if (($module = ltrim($item["link"], "/")) != "") {
						if ($this->user->access_allowed($module) == false) {
							continue;
						}
					}
				}

				$element["id"] = $item["id"];
				if ($current_url !== null) {
					$element["current"] = show_boolean($item["link"] == $current_url);
				}
				$element["text"] = $item["text"];
				$element["link"] = $item["link"];
				if ($depth > 1) {
					$element["submenu"] = $this->get_menu($item["id"], $depth - 1, $current_url);
				}

				array_push($result["items"], $element);
			}

			return $result;
		}

		/* Print menu to output
		 *
		 * INPUT:  array menu data
		 * OUTPUT: -
		 * ERROR:  -
		 */
		private function show_menu($menu) {
			 $this->output->open_tag("menu", array("id" => $menu["id"]));
			 foreach ($menu["items"] as $item) {
				$args = array("id" => $item["id"]);
				if (isset($item["current"])) {
					$args["current"] = $item["current"];
				}

				$this->output->open_tag("item", $args);
				$this->output->add_tag("link", $item["link"]);
				$this->output->add_tag("text", $item["text"]);
				if (isset($item["submenu"])) {
					$this->show_menu($item["submenu"]);
				}
				$this->output->close_tag();
			 }
			 $this->output->close_tag();
		}

		/* Appent menu to XML output
		 *
		 * INPUT:  [string link of active menu item for highlighting]
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function to_output($current_url = null) {
			/* Handle menu updates
			 */
			$cache = new cache($this->db, "menu");
			if ($cache->last_updated === null) {
				$cache->store("last_updated", time(), 365 * DAY);
			}
			if (isset($_SESSION["menu_last_updated"]) == false) {
				$_SESSION["menu_last_updated"] = $cache->last_updated;
			} else if ($cache->last_updated > $_SESSION["menu_last_updated"]) {
				$_SESSION["menu_cache"] = array();
				$_SESSION["menu_last_updated"] = $cache->last_updated;
			}
			unset($cache);

			/* Build menu
			 */
			if (isset($_SESSION["menu_cache"]) == false) {
				$_SESSION["menu_cache"] = array();
			}
			$cache = &$_SESSION["menu_cache"];

			$username = ($this->user !== null) ? $this->user->username : "";
			$index = md5(sprintf("%d-%d-%s-%s", $this->parent_id, $this->depth, $username, $current_url));

			if (isset($cache[$index]) == false) {
				if (($menu = $this->get_menu($this->parent_id, $this->depth, $current_url)) === false) {
					return false;
				}
				$cache[$index] = json_encode($menu);
			} else {
				$menu = json_decode($cache[$index], true);
			}

			$this->show_menu($menu);

			return true;
		}
	}
?>
