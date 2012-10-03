<?php
	class admin_page_controller extends controller {
		private function show_page_overview() {
			if (($pages = $this->model->get_pages()) === false) {
				$this->output->add_tag("result", "Database error.");
			} else {
				$this->output->open_tag("overview");
				$this->output->open_tag("pages");
				foreach ($pages as $page) {
					$page["visible"] = show_boolean($page["visible"]);
					$this->output->record($page, "page");
				}
				$this->output->close_tag();
				$this->output->close_tag();
			}
		}

		private function show_page_form($page) {	
			global $supported_languages;

			$this->output->set_xslt_parameter("admin_role_id", ADMIN_ROLE_ID);

			$page["private"] = show_boolean($page["private"]);
			$page["visible"] = show_boolean($page["visible"]);
			$page["back"] = show_boolean($page["back"]);

			$args = array();
			if (isset($page["id"])) {
				$args["id"] = $page["id"];
			}

			$this->output->add_javascript("ckeditor/ckeditor.js");
			$this->output->add_javascript("start_ckeditor.js");

			$this->output->open_tag("edit");

			/* Languages
			 */
			$this->output->open_tag("languages");
			foreach ($supported_languages as $code => $lang) {
				$this->output->add_tag("language", $lang, array("code" => $code));
			}
			$this->output->close_tag();

			/* Layouts
			 */
			$this->output->open_tag("layouts", array("current" => $page["layout"]));
			if (($layouts = $this->model->get_layouts()) != false) {
				foreach ($layouts as $layout) {
					$this->output->add_tag("layout", $layout);
				}
			}
			$this->output->close_tag();

			/* Roles
			 */
			$this->output->open_tag("roles");
			if (($roles = $this->model->get_roles()) != false) {
				foreach ($roles as $role) {
					$this->output->add_tag("role", $role["name"], array(
						"id"      => $role["id"],
						"checked" => show_boolean($page["roles"][$role["id"]])));
				}
			}
			$this->output->close_tag();

			/* Page data
			 */
			$this->output->record($page, "page", $args);

			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Save page") {
					/* Save page
					 */
					$_POST["url"] = "/".trim($_POST["url"], "/ ");
					if ($this->model->save_oke($_POST) == false) {
						$this->show_page_form($_POST);
					} else if (isset($_POST["id"]) == false) {
						/* Create page
						 */
						if ($this->model->create_page($_POST) === false) {
							$this->output->add_message("Database error while creating page.");
							$this->show_page_form($_POST);
						} else {
							$this->user->log_action("page %s created", $_POST["url"]);
							$this->show_page_overview();
						}
					} else {
						/* Update user
						 */
						$url = $this->model->get_url($_POST["id"]);

						if ($this->model->update_page($_POST, $_POST["id"]) === false) {
							$this->output->add_message("Database error while updating page.");
							$this->show_page_form($_POST);
						} else {
							if ($_POST["url"] == $url) {
								$name = $_POST["url"];
							} else {
								$name = sprintf("%s -> %s", $url, $_POST["url"]);
							}
							$this->user->log_action("page %s updated", $name);

							if ($this->settings->hiawatha_cache_time > 0) {
								if ($_POST["url"] == "/".$this->settings->start_page) {
									header("X-Hiawatha-Cache-Remove: all");
								} else {
									header("X-Hiawatha-Cache-Remove: ".$_POST["url"]);
								}
							}

							$this->show_page_overview();
						}
					}
				} else if ($_POST["submit_button"] == "Delete page") {
					/* Delete page
					 */
					$url = $this->model->get_url($_POST["id"]);

					if ($this->model->delete_page($_POST["id"]) == false) {
						$this->output->add_tag("result", "Database error while deleting page.");
					} else {
						$this->user->log_action("page %s deleted", $url);
						$this->show_page_overview();
					}
				} else {
					$this->show_page_overview();
				}
			} else if ($this->page->pathinfo[2] == "new") {
				/* Show the user webform
				 */
				$page = array(
					"url"      => "/",
					"language" => $this->settings->default_language,
					"layout"   => null,
					"visible"  => 1,
					"roles"    => array());
				$this->show_page_form($page);
			} else if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Show the user webform
				 */
				if (($page = $this->model->get_page($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "Page not found.");
				} else {
					$this->show_page_form($page);
				}
			} else {
				/* Show a list of all users
				 */
				$this->show_page_overview();
			}
		}
	}
?>
