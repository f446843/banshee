<?php
	require_once("../helpers/output.php");

	class admin_faq_controller extends controller {
		public function show_faq_overview() {
			if (($sections = $this->model->get_all_sections()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			} else if (($faqs = $this->model->get_all_faqs()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$this->output->open_tag("overview");
			
			$this->output->open_tag("sections");
			foreach ($sections as $section) {
				$this->output->add_tag("section", $section["label"], array("id" => $section["id"]));
			}
			$this->output->close_tag();

			$this->output->open_tag("faqs");
			foreach ($faqs as $faq) {
				$faq["question"] = truncate_text($faq["question"], 140);
				$this->output->record($faq, "faq");
			}
			$this->output->close_tag();

			$this->output->close_tag();
		}

		public function show_faq_form($faq) {
			if (($sections = $this->model->get_all_sections()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			if (isset($faq["select"]) == false) {
				$faq["select"] = count($sections) == 0 ? "new" : "old";
			}

			$this->output->add_javascript("ckeditor/ckeditor.js");
			$this->output->add_javascript("start_ckeditor.js");

			$this->output->open_tag("edit");

			$this->output->open_tag("sections");
			foreach ($sections as $section) {
				$this->output->add_tag("section", $section["label"], array("id" => $section["id"]));
			}
			$this->output->close_tag();

			$this->output->record($faq, "faq");

			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Save FAQ") {
					/* Save FAQ
					 */
					if ($this->model->save_oke($_POST) == false) {
						$this->show_faq_form($_POST);
					} else if (isset($_POST["id"]) === false) {
						/* Create FAQ
						 */
						if ($this->model->create_faq($_POST) == false) {
							$this->output->add_message("Error while creating F.A.Q.");
							show_faq_form($_POST);
						} else {
							$this->user->log_action("faq %d created", $this->db->last_insert_id);
							$this->show_faq_overview();
						}
					} else {
						/* Update FAQ
						 */
						if ($this->model->update_faq($_POST) == false) {
							$this->output->add_message("Error while updating F.A.Q.");
							$this->show_faq_form($_POST);
						} else {
							$this->user->log_action("faq %d updated", $_POST["id"]);
							$this->show_faq_overview();
						}
					}
				} else if ($_POST["submit_button"] == "Delete FAQ") {
					/* Delete FAQ
					 */
					if ($this->model->delete_faq($_POST["id"]) == false) {
						$this->output->add_message("Error while deleting F.A.Q.");
						show_faq_form($_POST);
					} else {
						$this->user->log_action("faq %d deleted", $_POST["id"]);
						show_faq_overview();
					}
				} else {
					$this->show_faq_overview();
				}
			} else if ($this->page->pathinfo[2] == "new") {
				/* New FAQ
				 */
				$faq = array("section" => 1);
				$this->show_faq_form($faq);
			} else if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Edit existing FAQ
				 */
				if (($faq = $this->model->get_faq($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "FAQ not found.");
				} else {
					$this->show_faq_form($faq);
				}
			} else {
				/* FAQ overview
				 */
				$this->show_faq_overview();
			}
		}
	}
?>
