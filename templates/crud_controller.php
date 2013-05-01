<?php
	class XXX_controller extends controller {
		private function show_overview() {
			if (($xxx_count = $this->model->count_xxxs()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$paging = new pagination($this->output, "xxxs", $this->settings->admin_page_size, $xxx_count);

			if (($XXXs = $this->model->get_XXXs($paging->offset, $paging->size)) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$this->output->open_tag("overview");

			$this->output->open_tag("XXXs");
			foreach ($XXXs as $XXX) {
				$this->output->record($XXX, "XXX");
			}
			$this->output->close_tag();

			$paging->show_browse_links();

			$this->output->close_tag();
		}

		private function show_XXX_form($XXX) {
			$this->output->open_tag("edit");
			$this->output->record($XXX, "XXX");
			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Save XXX") {
					/* Save XXX
					 */
					if ($this->model->save_oke($_POST) == false) {
						$this->show_XXX_form($_POST);
					} else if (isset($_POST["id"]) === false) {
						/* Create XXX
						 */
						if ($this->model->create_XXX($_POST) === false) {
							$this->output->add_message("Error creating XXX.");
							$this->show_XXX_form($_POST);
						} else {
							$this->user->log_action("XXX created");
							$this->show_overview();
						}
					} else {
						/* Update XXX
						 */
						if ($this->model->update_XXX($_POST) === false) {
							$this->output->add_message("Error updating XXX.");
							$this->show_XXX_form($_POST);
						} else {
							$this->user->log_action("XXX updated");
							$this->show_overview();
						}
					}
				} else if ($_POST["submit_button"] == "Delete XXX") {
					/* Delete XXX
					 */
					if ($this->model->delete_oke($_POST) == false) {
						$this->show_XXX_form($_POST);
					} else if ($this->model->delete_XXX($_POST["id"]) === false) {
						$this->output->add_message("Error deleting XXX.");
						$this->show_XXX_form($_POST);
					} else {
						$this->user->log_action("XXX deleted");
						$this->show_overview();
					}
				} else {
					$this->show_overview();
				}
			} else if ($this->page->pathinfo[1] === "new") {
				/* New XXX
				 */
				$XXX = array();
				$this->show_XXX_form($XXX);
			} else if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Edit XXX
				 */
				if (($XXX = $this->model->get_XXX($this->page->pathinfo[1])) === false) {
					$this->output->add_tag("result", "XXX not found.\n");
				} else {
					$this->show_XXX_form($XXX);
				}
			} else {
				/* Show overview
				 */
				$this->show_overview();
			}
		}
	}
?>
