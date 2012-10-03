<?php
	class admin_menu_controller extends controller {
		private $url = null;

		private function show_menu_form($menu_id, $menu) {
			$this->output->add_javascript("jquery/jquery.js");
			$this->output->add_javascript("jquery/jquery.ui.core.js");
			$this->output->add_javascript("jquery/jquery.ui.widget.js");
			$this->output->add_javascript("jquery/jquery.ui.mouse.js");
			$this->output->add_javascript("jquery/jquery.ui.sortable.js");
			$this->output->add_javascript("admin/menu.js");

			$this->output->open_tag("edit");

			if (($parent = $this->model->get_menu($menu_id)) != false) {
				$this->output->add_tag("parent", $parent["text"], array("id" => $parent["parent_id"]));
			}

			$this->output->open_tag("menu", array("id" => $menu_id));
			$max_id = 0;
			foreach ($menu as $item) {
				unset($item["parent_id"]);
				$this->output->record($item, "item");
				if ($item["id"] > $max_id) {
					$max_id = $item["id"];
				}
			}
			$this->output->close_tag();
			$this->output->add_tag("max_menu_id", $max_id + 1);
			$this->output->close_tag();

			if (count($menu) == 0) {
				$this->output->onload_javascript("add_item('editmenu', 1)");
			}
		}

		public function execute() {
			$this->url = array("url" => "admin/menu");

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				/* Update menu
				 */
				if ($this->model->menu_oke($_POST) == false) {
					foreach ($_POST["menu"] as $id => $value) {
						$_POST["menu"][$id]["id"] = $id;
					}
					$this->show_menu_form($_POST["menu_id"], $_POST["menu"]);
				} else if ($this->model->update_menu($_POST["menu_id"], $_POST["menu"]) == false) {
					$this->output->add_tag("result", "Error while updating menu.", $url);
				} else {
					$this->output->add_tag("result", "The menu has been updated.", array("url" => "admin/menu/".$_POST["menu_id"]));
					$this->output->remove_from_cache("menu");
					$this->user->log_action("menu %d updated", $_POST["menu_id"]);
					header("X-Hiawatha-Cache-Remove: all");
				}
			} else {
				/* Show menu
				 */
				if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY) == false) {
					$menu_id = 0;
				} else if (($menu_id = (int)$this->page->pathinfo[2]) != 0) {
					if (($parent = $this->model->get_menu($menu_id)) == false) {
						$this->output->add_tag("result", "Menu not found.", $url);
						return;
					}
				}

				if (($menu = $this->model->get_menu_items($menu_id)) === false) {
					$this->output->add_tag("result", "Database error!", $url);
				} else {
					$this->show_menu_form($menu_id, $menu);
				}
			}
		}
	}
?>
