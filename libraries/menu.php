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

		/* Get menu by identifier
		 *
		 * INPUT:  int menu identifier[, string link text]
		 * OUTPUT: array( menu )
		 * ERROR:  false
		 */
		public function get_menu($id, $text = null) {
			$params = array($id);
			if ($text == null) {
				$selector = "%d";
			} else {
				$selector = "(select id from menu where parent_id=%d and text=%s) ";
				array_push($params, $text);
			}
			array_push($params, "order");

			$query = "select * from menu where parent_id=".$selector." order by %S";

			return $this->db->execute($query, $params);
		}

		/* Get menu by link text
		 *
		 * INPUT:  string text[, int parent identifier]
		 * OUTPUT: array( menu )
		 * ERROR:  false
		 */
		public function get_menu_by_text($text, $parent_id = 0) {
			$query = "select id from menu where text=%s and parent_id=%d limit 1";
			if (($menu = $this->db->execute($query, $text, $parent_id)) == false) {
				return false;
			}

			return $menu[0]["id"];
		}

		/* Get menu by link
		 *
		 * INPUT:  string menu link[, int parent identifier]
		 * OUTPUT: array( menu )
		 * ERROR:  false
		 */
		public function get_menu_by_link($link, $parent_id = 0) {
			$query = "select id from menu where link=%s and parent_id=%d limit 1";
			if (($menu = $this->db->execute($query, $link, $parent_id)) == false) {
				return false;
			}

			return $menu[0]["id"];
		}

		/* Appent menu to XML output
		 *
		 * INPUT:  int menu identifier[, int menu depth][, string link of active menu item for highlighting]
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function to_output($id, $depth = 1, $current_url = null) {
			if (($menu = $this->get_menu($id)) == false) {
				return false;
			}

			$this->output->open_tag("menu", array("id" => $id));
			foreach ($menu as $item) {
				$args = array("id" => $item["id"]);
				if ($current_url !== null) {
					$args["current"] = show_boolean($item["link"] == $current_url);
				}

				$this->output->open_tag("item", $args);
				$this->output->add_tag("link", $item["link"]);
				$this->output->add_tag("text", $item["text"]);
				if ($depth > 1) {
					$this->to_output($item["id"], $depth - 1, $current_url);
				}
				$this->output->close_tag();
			}
			$this->output->close_tag();

			return true;
		}
	}
?>
