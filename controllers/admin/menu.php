<?php
	class admin_menu_controller extends controller {
		private function show_menu($menu) {
			$this->output->open_tag("branch");
			foreach ($menu as $item) {
				$this->output->open_tag("item");
				$this->output->add_tag("text", $item["text"]);
				$this->output->add_tag("link", $item["link"]);
				if (isset($item["submenu"])) {
					$this->show_menu($item["submenu"]);
				}
				$this->output->close_tag();
			}
			$this->output->close_tag();
		}

		private function show_menu_form($menu) {
			$this->output->add_javascript("jquery/jquery.js");
			$this->output->add_javascript("jquery/jquery-ui.js");
			$this->output->add_javascript("banshee/jquery.menueditor.js");
			$this->output->add_javascript("admin/menu.js");

			$this->output->add_css("banshee/menueditor.css");

			$this->output->open_tag("edit");
			$this->show_menu($menu);
			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				/* Update menu
				 */
				if ($this->model->menu_oke($_POST["menu"]) == false) {
					$this->show_menu_form($_POST["menu"]);
				} else if ($this->model->update_menu($_POST["menu"]) == false) {
					$this->output->add_tag("result", "Error while updating menu.");
				} else {
					$this->output->add_tag("result", "The menu has been updated.");
					$this->user->log_action("menu updated");
					header("X-Hiawatha-Cache-Remove: all");

					$cache = new cache($this->db, "menu");
					$cache->store("last_updated", time(), 365 * DAY);
				}
			} else {
				/* Show menu
				 */
				if (($menu = $this->model->get_menu()) === false) {
					$this->output->add_tag("result", "Error loading menu.");
				} else {	
					$this->show_menu_form($menu);
				}
			}
		}
	}
?>
