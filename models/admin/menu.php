<?php
	class admin_menu_model extends model {
		private function structure_menu($menuitems, $parent_id) {
			$menu = array();

			foreach ($menuitems as $item) {
				if ($item["parent_id"] == $parent_id) {
					$new = array(
						"text" => $item["text"],
						"link" => $item["link"]);
					$submenu = $this->structure_menu($menuitems, $item["id"]);
					if (count($submenu) > 0) {
						$new["submenu"] = $submenu;
					}
					array_push($menu, $new);
				}
			}

			return $menu;
		}

		public function get_menu() {
			$query = "select * from menu order by id";
			if (($menuitems = $this->db->execute($query)) === false) {
				return false;
			}

			return $this->structure_menu($menuitems, 0);
		}

		public function menu_oke($menu) {
			$result = true;

			if (is_array($menu)) {
				foreach ($menu as $item) {
					if ((trim($item["text"]) == "") || (trim($item["link"]) == "")) {
						$this->output->add_message("The text or link of a menu item can't be empty.");
						$result = false;
					}

					if (isset($item["submenu"])) {
						if ($this->menu_oke($item["submenu"]) == false) {
							$result = false;
						}
					}
				}
			}

			return $result;
		}

		private function save_menu($menu, $parent_id) {
			foreach ($menu as $item) {
				$new = array(
					"id"        => null,
					"parent_id" => $parent_id,
					"text"      => $item["text"],
					"link"      => $item["link"]);
				if ($this->db->insert("menu", $new) === false) {
					return false;
				}

				if (isset($item["submenu"])) {
					if ($this->save_menu($item["submenu"], $this->db->last_insert_id) == false) {
						return false;
					}
				}
			}

			return true;
		}

		public function update_menu($menu) {
			$this->db->query("begin");

			if ($this->db->execute("truncate table %S", "menu") === false) {
				$this->db->query("rollback");
				return false;
			}

			if (is_array($menu)) {
				if ($this->save_menu($menu, 0) == false) {
					$this->db->query("rollback");
					return false;
				}
			}

			return $this->db->query("commit") != false;
		}
	}
?>
