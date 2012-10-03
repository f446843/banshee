<?php
	class admin_user_model extends model {
		public function count_users() {
			$query = "select count(*) as count from users ".
				($this->user->is_admin ? "" : "where organisation_id=%d ").
				"order by username";

			if (($result = $this->db->execute($query, $this->user->organisation_id)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_users($offset, $limit) {
			$query = "select * from users ";
			$args = array();
			if ($this->user->is_admin == false) {
				$query .= "where organisation_id=%d ";
				array_push($args, $this->user->organisation_id);
			}
			$query .= "order by username limit %d,%d";
			array_push($args, $offset, $limit);

			if (($users = $this->db->execute($query, $args)) === false) {
				return false;
			}

			$query = "select * from user_role where user_id=%d and role_id=%d";
			foreach ($users as $i => $user) {
				if (($role = $this->db->execute($query, $user["id"], ADMIN_ROLE_ID)) === false) {
					return false;
				}
				$users[$i]["is_admin"] = count($role) > 0;
			}

			return $users;
		}

		public function get_user($user_id) {
			static $users = array();

			if (isset($users[$user_id])) {
				return $users[$user_id];
			}

			if (($user = $this->db->entry("users", $user_id)) == false) {
				$this->user->log_action("requested non-existing user %s", $user_id);
				return false;
			}

			$query = "select role_id from user_role where user_id=%d";
			if (($roles = $this->db->execute($query, $user_id)) === false) {
				return false;
			}

			$user["roles"] = array();
			foreach ($roles as $role) {
				array_push($user["roles"], $role["role_id"]);
			}

			$users[$user_id] = $user;

			return $user;
		}

		public function get_username($user_id) {
			if (($user = $this->db->entry("users", $user_id)) == false) {
				return false;
			}

			return $user["username"];
		}

		public function get_organisations() {
			$query = "select * from organisations order by name";

			return $this->db->execute($query);
		}

		public function get_roles() {
			$query = "select * from roles order by name";

			return $this->db->execute($query);
		}

		public function access_allowed_for_non_admin($user) {
			if (in_array(ADMIN_ROLE_ID, $user["roles"])) {
				return false;
			}
			
			if ($user["organisation_id"] != $this->user->organisation_id) {
				return false;
			}

			return true;
		}

		public function save_oke($user) {
			$result = true;

			if (isset($user["id"])) {
				if (($current = $this->get_user($user["id"])) == false) {
					$this->output->add_message("User not found.");
					return false;
				}

				/* Non-admins cannot edit admins
				 */
				if ($this->user->is_admin == false) {
					if ($this->access_allowed_for_non_admin($current) == false) {
						$this->output->add_message("You are not allowed to edit this user.");
						$this->user->log_action("unauthorized update attempt of user %d", $user["id"]);
						return false;
					}
				}

				/* Username changed need password to be reset
				 */
				if (($user["username"] != $current["username"]) && ($user["password"] == "")) {
					$this->output->add_message("Username change needs password to be re-entered.");
					$result = false;
				}
			}

			/* Check username
			 */
			if (($user["username"] == "") || ($user["fullname"] == "")) {
				$this->output->add_message("The username and full name cannot be empty.");
				$result = false;
			} else if (valid_input($user["username"], VALIDATE_LETTERS.VALIDATE_NUMBERS) == false) {
				$this->output->add_message("Invalid characters in username.");
				$result = false;
			} else if (($check = $this->db->entry("users", $user["username"], "username")) != false) {
				if ($check["id"] != $user["id"]) {
					$this->output->add_message("Username already exists.");
					$result = false;
				}
			}

			/* Check password
			 */
			if (isset($user["id"]) == false) {
				if (($user["password"] == "") && is_false($user["generate"])) {
					$this->output->add_message("Fill in the password or let Banshee generate one.");
					$result = false;
				}
			}

			/* Check e-mail
			 */
			if (valid_email($user["email"]) == false) {
				$this->output->add_message("Invalid e-mail address.");
				$result = false;
			} else if (($check = $this->db->entry("users", $user["email"], "email")) != false) {
				if ($check["id"] != $user["id"]) {
					$this->output->add_message("E-mail address already exists.");
					$result = false;
				}
			}

			return $result;
		}

		private function assign_roles_to_user($user) {
			if ($this->db->query("delete from user_role where user_id=%d", $user["id"]) == false) {
				return false;
			}

			if (is_array($user["roles"])) {
				foreach ($user["roles"] as $role_id) {
					/* Non-admins can't assign the admin role
					 */
					if (($this->user->is_admin == false) && ($role_id == ADMIN_ROLE_ID)) {
						$this->user->log_action("unauthorized admininstrator role assignment for user %d", $user["id"]);
						continue;
					}
					if ($this->db->query("insert into user_role values (%d, %d)", $user["id"], $role_id) == false) {
						return false;
					}
				}
			}

			return true;
		}

		public function create_user($user) {
			$keys = array("id", "organisation_id", "username", "password", "one_time_key", "status", "fullname", "email");

			$user["id"] = null;
			if ($this->user->is_admin == false) {
				$user["organisation_id"] = $this->user->organisation_id;
			}
			$user["one_time_key"] = null;

			if ($this->db->query("begin") == false) {
				return false;
			}

			if ($this->db->insert("users", $user, $keys) === false) {
				$this->db->query("rollback");
				return false;
			}
			$user["id"] = $this->db->last_insert_id;

			if ($this->assign_roles_to_user($user) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function update_user($user) {
			$keys = array("username", "fullname", "email");
			if ($user["password"] != "") {
				array_push($keys, "password");
			}
			if ($this->user->is_admin) {
				array_push($keys, "organisation_id");
			}
			if (is_array($user["roles"]) == false) {
				$user["roles"] = array();
			}
			if ($this->user->id != $user["id"]) {
				array_push($keys, "status");
			} else if (($current = $this->get_user($user["id"])) == false) {
				return false;
			} else if (in_array(ADMIN_ROLE_ID, $current["roles"]) && (in_array(ADMIN_ROLE_ID, $user["roles"]) == false)) {
				array_unshift($user["roles"], ADMIN_ROLE_ID);
			}

			if ($this->db->query("begin") == false) {
				return false;
			}

			if ($this->db->update("users", $user["id"], $user, $keys) === false) {
				$this->db->query("rollback");
				return false;
			}

			if ($this->assign_roles_to_user($user) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		private function table_exists($table) {
			static $tables = null;

			if ($tables === null) {
				if (($result = $this->db->execute("show tables")) === false) {
					return false;
				}

				$tables = array();
				foreach ($result as $entry) {
					array_push ($tables, $entry["Tables_in_".DB_DATABASE]);
				}
			}

			return in_array($table, $tables);
		}

		public function delete_oke($user_id) {
			$result = true;

			if ($user_id == $this->user->id) {
				$this->output->add_message("You are not allowed to delete your own account.");
				$result = false;
			}

			if ($this->user->is_admin == false) {
				if (($current = $this->get_user($user_id)) == false) {
					$this->output->add_message("User not found.");
					$result = false;
				}

				if ($this->access_allowed_for_non_admin($current) == false) {
					$this->output->add_message("You are not allowed to delete this user.");
					$this->user->log_action("unauthorized delete attempt of user %d", $user_id);
					$result = false;
				}
			}

			if ($this->table_exists("weblogs")) {
				$query = "select count(*) as count from weblogs where user_id=%d";
				if (($result = $this->db->execute($query, $user_id)) === false) {
					$this->output->add_message("Database error.");
					$result = false;
				} else if ($result[0]["count"] > 0) {
					$this->output->add_message("This user has weblog messages to its name.");
					$result = false;
				}
			}

			return $result;
		}

		public function delete_user($user_id) {
			if ($this->db->query("begin") === false) {
				return false;
			}

			/* Forum last view
			 */
			if ($this->table_exists("forum_last_view")) {
				if ($this->db->query("delete from forum_last_view where user_id=%d", $user_id) === false) {
					$this->db->query("rollback");
					return false;
				}
			}

			/* Forum messages
			 */
			if ($this->table_exists("forum_messages")) {
				if (($user = $this->db->entry("users", $user_id)) === false) {
					$this->db->query("rollback");
					return false;
				}

				$query = "update forum_messages set user_id=null, username=%s where user_id=%d";
				if ($this->db->execute($query, $user["fullname"], $user_id) === false) {
					$this->db->query("rollback");
					return false;
				}
			}

			/* Sessions
			 */
			if ($this->db->query("delete from sessions where user_id=%d", $user_id) === false) {
				$this->db->query("rollback");
				return false;
			}

			/* Roles
			 */
			if ($this->db->query("delete from user_role where user_id=%d", $user_id) === false) {
				$this->db->query("rollback");
				return false;
			}

			if ($this->db->query("delete from users where id=%d", $user_id) === false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") !== false;
		}

		public function send_notification($user) {
			if (isset($user["id"]) == false) {
				$type = "created";
			} else {
				$type = "updated";
			}

			if (($message = file_get_contents("../extra/account_".$type.".txt")) === false) {
				return;
			}

			$replace = array(
				"USERNAME" => $user["username"],
				"PASSWORD" => $user["plaintext"],
				"FULLNAME" => $user["fullname"],
				"HOSTNAME" => $_SERVER["SERVER_NAME"],
				"TITLE"    => $this->settings->head_title);

			$email = new email("Account ".$type, $this->settings->webmaster_email);
			$email->set_message_fields($replace);
			$email->message($message);

			return $email->send($user["email"], $user["fullname"]);
		}
	}
?>
