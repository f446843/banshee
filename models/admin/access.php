<?php
	class admin_access_model extends model {
		public function get_all_users() {
			$query = "select id,username from users order by username";
			if (($users = $this->db->execute($query)) === false) {
				return false;
			}

			$query = "select role_id from user_role where user_id=%d";
			foreach ($users as $i => $user) {
				if (($roles = $this->db->execute($query, $user["id"])) === false) {
					return false;
				}
				$users[$i]["roles"] = array_flatten($roles);
			}

			return $users;
		}

		public function get_private_modules() {
			if (($columns = $this->db->execute("show columns from %S", "roles")) === false) {
				return false;
			}

			$result = array();
			foreach ($columns as $column) {
				if (strstr($column["Type"], "tinyint") !== false) {
					array_push($result, $column["Field"]);
				}
			}
			sort($result);

			return $result;
		}

		public function get_private_pages() {
			$query = "select id, url from pages where private=%d order by url";
			if (($pages = $this->db->execute($query, 1)) === false) {
				return false;
			}

			$result = array();
			$query = "select * from page_access where page_id=%d";
			foreach ($pages as $page) {
				$page["access"] = array(ADMIN_ROLE_ID => 1);
				if (($access = $this->db->execute($query, $page["id"])) != false) {
					foreach ($access as $right) {
						$page["access"][$right["role_id"]] = $right["level"];
					}
				}
				array_push($result, $page);
			}

			return $result;
		}

		public function get_all_roles() {
			$query = "select * from roles r order by name";

			return $this->db->execute($query);
		}
	}
?>
