<?php
	require_once("../libraries/helpers/output.php");

	class admin_forum_controller extends controller {
		private function show_message_overview() {
			if (($message_count = $this->model->count_messages()) === false) {
				$this->output->add_tag("result", "Database error.");
				return false;
			}

			$paging = new pagination($this->output, "admin_forum", $this->settings->admin_page_size, $message_count);

			if (($messages = $this->model->get_messages($paging->offset, $paging->size)) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$this->output->open_tag("overview");

			$this->output->open_tag("messages");
			foreach ($messages as $message) {
				$message["content"] = truncate_text($message["content"], 400);
				$message["timestamp"] = date("j F Y, H:i", $message["timestamp"]);
				if ($message["author"] == "") {
					$message["author"] = $message["username"];
				}
				$this->output->record($message, "message");
			}
			$this->output->close_tag();

			$paging->show_browse_links();

			$this->output->close_tag();
		}

		private function show_message_form($message) {
			$this->output->open_tag("edit");
			$this->output->record($message, "message");
			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Save message") {
					/* Update message
					 */
					if ($this->model->save_oke($_POST) == false) {
						$this->show_message_form($_POST);
					} else if ($this->model->update_message($_POST) === false) {
						$this->output->add_message("Database error while updating message.");
						$this->show_message_form($_POST);
					} else {
						$topic_id = $this->model->get_topic_id($_POST["id"]);
						$this->user->log_action("forum message %d (topic:%d) updated", $_POST["id"], $topic_id);
						$this->show_message_overview();
					}
				} else if ($_POST["submit_button"] == "delete") {
					/* Delete message
					 */
					$topic_id = $this->model->get_topic_id($_POST["id"]);

					if ($this->model->delete_message($_POST["message_id"]) == false) {
						$this->output->add_tag("result", "Database error while deleting message.");
					} else {
						$this->user->log_action("forum message %d (topic:%d) deleted", $_POST["message_id"], $topic_id);
						$this->show_message_overview();
					}
				} else {
					$this->show_message_overview();
				}
			} else if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Edit existing message
				 */
				if (($message = $this->model->get_message($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "Message not found.");
				} else {
					$this->show_message_form($message);
				}
			} else {
				/* Show message overview
				 */
				$this->show_message_overview();
			}
		}
	}
?>
