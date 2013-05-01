<?php
	class admin_dictionary_controller extends controller {
		private function show_dictionary_overview() {
			if (($word_count = $this->model->count_words()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$paging = new pagination($this->output, "admin_dictionary", $this->settings->admin_page_size, $word_count);

			if (($words = $this->model->get_words($paging->offset, $paging->size)) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$this->output->open_tag("overview");

			$this->output->open_tag("words");
			foreach ($words as $word) {
				$this->output->record($word, "word");
			}
			$this->output->close_tag();

			$paging->show_browse_links();

			$this->output->close_tag();
		}

		private function show_word_form($word) {
			if (isset($word["id"]) != false) {
				$letter = strtolower($word["word"][0]);
			}

			$this->output->add_javascript("ckeditor/ckeditor.js");
			$this->output->add_javascript("banshee/start_ckeditor.js");

			$this->output->record($word, "edit");
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Save word") {
					/* Save word
					 */
					if ($this->model->save_oke($_POST) == false) {
						$this->show_word_form($_POST);
					} else if (isset($_POST["id"]) == false) {
						if ($this->model->create_word($_POST) == false) {
							$this->output->add_message("Database error while creating word.");
							$this->show_word_form($_POST);
						} else {
							$this->user->log_action("dictionary word %d created", $this->db->last_insert_id);
							$this->show_dictionary_overview();
						}
					} else {
						if ($this->model->update_word($_POST) == false) {
							$this->output->add_message("Database error while updating word.");
							$this->show_word_form($_POST);
						} else {
							$this->user->log_action("dictionary word %d updated", $_POST["id"]);
							$this->show_dictionary_overview();
						}
					}
				} else if ($_POST["submit_button"] == "Delete word") {
					/* Delete word
					 */
					if ($this->model->delete_word($_POST["id"]) == false) {
						$this->output->add_tag("result", "Error while deleting word.");
					} else {
						$this->user->log_action("dictionary word %d deleted", $_POST["id"]);
						$this->show_dictionary_overview();
					}
				} else {
					$this->show_dictionary_overview();
				}
			} else if ($this->page->pathinfo[2] == "new") {
				/* New word
				 */
				$word = array();
				$this->show_word_form($word);
			} else if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Edit word
				 */
				if (($word = $this->model->get_word($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "Word not found.");
				} else {
					$this->show_word_form($word);
				}
			} else {
				/* Show dictionary overview
				 */
				$this->show_dictionary_overview();
			}
		}
	}
?>
