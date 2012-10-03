<?php
	class admin_role_controller extends controller {
		public function show_role_overview() {
			if (($roles = $this->model->get_all_roles()) === false) {
				$this->output->add_tag("result", "Database error.");
			} else {
				$this->output->open_tag("overview");

				$this->output->open_tag("roles");
				foreach ($roles as $role) {
					$this->output->add_tag("role", $role["name"], array("id" => $role["id"], "users" => $role["users"]));
				}
				$this->output->close_tag();

				$this->output->close_tag();
			}
		}

		public function show_role_form($role) {
			if (isset($role["id"]) == false) {
				$params = array(
					"editable" => "yes");
			} else {
				$params = array(
					"id"       => $role["id"],
					"editable" => show_boolean($role["id"] != ADMIN_ROLE_ID));
			}

			if (($pages = $this->model->get_restricted_pages()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}
			sort($pages);

			$this->output->open_tag("edit");

			/* Read-Write
			 */
			$access = array(
				ACCESS_NO       => "--",
				ACCESS_READONLY => "ro",
				ACCESS_YES      => "rw");
			$this->output->open_tag("readwrite");
			foreach ($access as $value => $label) {
				$this->output->add_tag("value", $label, array("value" => $value));
			}
			$this->output->close_tag();

			$read_write = oproar_pages();

			/* Roles
			 */
			$this->output->add_tag("role", $role["name"], $params);
			$this->output->open_tag("pages");
			foreach ($pages as $page) {
				$this->output->add_tag("page", $page, array(
					"type"  => in_array($page, $read_write) ? "select" : "checkbox",
					"value" => $role[$page]));
			}
			$this->output->close_tag();

			$this->output->open_tag("members");
			if (($users = $this->model->get_role_members($role["id"])) !== false) {
				foreach ($users as $user) {
					$this->output->open_tag("member", array("id" => $user["id"]));
					$this->output->add_tag("fullname", $user["fullname"]);
					$this->output->add_tag("email", $user["email"]);
					$this->output->close_tag();
				}
			}
			$this->output->close_tag();

			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Save role") {
					/* Save role
					 */
					if ($this->model->save_oke($_POST) == false) {
						$this->show_role_form($_POST);
					} else if (isset($_POST["id"]) == false) {
						/* Create role
						 */
						if ($this->model->create_role($_POST) === false) {
							$this->output->add_message("Database error while creating role.");
							$this->show_role_form($_POST);
						} else {
							$this->user->log_action("role %d created", $this->db->last_insert_id);
							$this->show_role_overview();
						}
					} else {
						/* Update role
						 */
						if ($this->model->update_role($_POST) === false) {
							$this->output->add_message("Database error while updating role.");
							$this->show_role_form($_POST);
						} else {
							$this->user->log_action("role %d updated", $_POST["id"]);
							$this->show_role_overview();
						}
					}
				} else if ($_POST["submit_button"] == "Delete role") {
					/* Delete role
					 */
					if ($this->model->delete_oke($_POST) == false) {
						$this->output->add_tag("result", "This role cannot be deleted.");
					} else if ($this->model->delete_role($_POST["id"]) == false) {
						$this->output->add_tag("result", "Database error while deleting role.");
					} else {
						$this->user->log_action("role %d deleted", $_POST["id"]);
						$this->show_role_overview();
					}
				} else {
					$this->show_role_overview();
				}
			} else if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Show the role webform
				 */
				if (($role = $this->model->get_role($this->page->pathinfo[2])) != false) {
					$this->show_role_form($role);
				} else {
					$this->output->add_tag("result", "Role not found.");
				}
			} else if ($this->page->pathinfo[2] == "new") {
				/* Show the role webform
				 */
				$role = array("profile" => true);
				$this->show_role_form($role);
			} else {
				/* Show a list of all roles
				 */
				$this->show_role_overview();
			}
		}
	}
?>
