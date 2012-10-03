<?php
	class admin_menu_model extends model {
		public function get_menu($menu_id) {
			return $this->db->entry("menu", $menu_id);
		}

		public function get_menu_items($parent_id) {
			$query = "select *,(select count(*) from menu where parent_id=m.id) as children ".
					 "from menu m where parent_id=%d order by %S";
			return $this->db->execute($query, $parent_id, "order");
		}

		public function menu_oke($data) {
			$result = true;

			if ($data["menu_id"] !== "0") {
				if ($this->db->entry("menu", $data["menu_id"]) == false) {
					$this->output->add_message("Menu not found.");
					$result = false;
				}
			}

			if (is_array($data["menu"])) {
				foreach ($data["menu"] as $item) {
					if (($item["text"] == "") && ($item["link"] != "")) {
						$this->output->add_message("Text can't be empty.");
						$result = false;
						break;
					}
				}
			}

			return $result;
		}

		public function update_menu($parent_id, $menu) {
			if (count($menu) == 0) {
				$query = "delete from menu where parent_id=%d";
				return $this->db->query($query, $parent_id) != false;
			}

			foreach ($menu as $id => $item) {
				if (trim($item["text"]) == "") {
					unset($menu[$id]);
				}
			}

			$this->db->query("begin");

			/* Delete items
			 */
			$query = "select * from menu where parent_id=%d";
			if (($current = $this->db->execute($query, $parent_id)) === false) {
				$this->db->query("rollback");
				return false;
			}

			$ids = array_keys($menu);
			foreach ($current as $item) {
				if (in_array($item["id"], $ids) == false) {
					if ($this->db->delete("menu", $item["id"]) === false) {
						$this->db->query("rollback");
						return false;
					}
				}
			}

			/* Add items
			 */
			$keys = array("id", "parent_id", "order", "text", "link");
			foreach ($menu as $id => $item) {
				if ($id < 0) {
					$item["id"] = null;
					$item["parent_id"] = $parent_id;
					$item["order"] = 10;
					if ($this->db->insert("menu", $item, $keys) === false) {
						$this->db->query("rollback");
						return false;
					}
					$menu[$id]["id"] = $this->db->last_insert_id;
				} else {
					$menu[$id]["id"] = $id;
				}
			}

			/* Sort menu
			 */
			$order = 1;
			$keys = array("order", "text", "link");
			foreach ($menu as $item) {
				$item["order"] = $order++;
				if ($this->db->update("menu", $item["id"], $item, $keys) === false) {
					$this->db->query("rollback");
					return false;
				}
			}

			return $this->db->query("commit") != false;
		}
	}
?>
