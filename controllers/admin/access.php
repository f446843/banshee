<?php
	class admin_access_controller extends controller {
		public function execute() {
			if (($users = $this->model->get_all_users()) === false) {
				$this->output->add_tag("result", "Database error.");
			} else if (($modules = $this->model->get_private_modules()) === false) {
				$this->output->add_tag("result", "Database error.");
			} else if (($pages = $this->model->get_private_pages()) === false) {
				$this->output->add_tag("result", "Database error.");
			} else if (($roles = $this->model->get_all_roles()) === false) {
				$this->output->add_tag("result", "Database error.");
			} else {
				$this->output->open_tag("overview");

				/* Roles
				 */
				$this->output->open_tag("roles");
				foreach ($roles as $role) {
					$this->output->add_tag("role", $role["name"]);
				}
				$this->output->close_tag();

				/* Users
				 */
				$this->output->open_tag("users");
				foreach ($users as $user) {
					$this->output->open_tag("user", array("name" => $user["username"]));
					foreach ($roles as $role) {
						$this->output->add_tag("role", in_array($role["id"], $user["roles"]) ? YES : NO);
					}
					$this->output->close_tag();
				}
				$this->output->close_tag();

				/* Modules
				 */
				$this->output->open_tag("modules");
				foreach ($modules as $module) {
					$this->output->open_tag("module", array("url" => $module));
					foreach ($roles as $role) {
						$this->output->add_tag("access", $role[$module]);
					}
					$this->output->close_tag();
				}
				$this->output->close_tag();

				/* Pages
				 */
				$this->output->open_tag("pages");
				foreach ($pages as $page) {
					$this->output->open_tag("page", array("url" => $page["url"]));
					foreach ($roles as $role) {
						$level = $page["access"][$role["id"]];
						$this->output->add_tag("access", isset($level) ? $level : 0);
					}
					$this->output->close_tag();
				}
				$this->output->close_tag();

				$this->output->close_tag();
			}
		}
	}
?>
