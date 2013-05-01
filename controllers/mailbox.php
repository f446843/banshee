<?php
	class mailbox_controller extends controller {
		private $title = "Mailbox";

		private function show_mails($mails, $column) {
			$this->output->open_tag("mailbox", array("column" => $column));
			foreach ($mails as $mail) {
				$mail["subject"] = truncate_text($mail["subject"], 55);
				$mail["timestamp"] = date_string("l, j F Y H:i:s", $mail["timestamp"]);
				$mail["read"] = $mail["read"] == YES ? "read" : "unread";
				$this->output->record($mail, "mail");
			}
			$this->output->close_tag();
		}

		private function show_inbox() {
			$this->title = "Inbox";

			if (($mails = $this->model->get_inbox()) === false) {
				$this->output->add_tag("result", "Error reading mailbox.");
			} else {
				$this->show_mails($mails, "From");
				$this->output->add_tag("link", "Show sentbox", array("url" => "/sent"));
			}
		}

		private function show_sentbox() {
			$this->title = "Sentbox";

			if (($mails = $this->model->get_sentbox()) === false) {
				$this->output->add_tag("result", "Error reading sentbox.");
			} else {
				$this->show_mails($mails, "To");
				$this->output->add_tag("link", "Show inbox", array("url" => ""));
			}
		}

		private function show_mail($mail) {
			$message = new message($mail["message"]);
			$mail["message"] = $message->unescaped_output();

			if ($mail["to_user_id"] == $this->user->id) {
				$this->title = "Inbox";
			} else {
				$this->title = "Sentbox";
				$back = "/sent";
			}

			$actions = show_boolean($mail["to_user_id"] == $this->user->id);
			$this->output->record($mail, "mail", array("actions" => $actions, "back" => $back));
		}

		private function write_mail($mail) {
			if (($recipients = $this->model->get_recipients()) === false) {
				$this->output->add_tag("result", "Error fetching recipient list.");
				return;
			}

			$this->output->open_tag("write");

			$this->output->open_tag("recipients");
			foreach ($recipients as $recipient) {
				$this->output->add_tag("recipient", $recipient["fullname"], array("id" => $recipient["id"]));
			}
			$this->output->close_tag();

			$this->output->record($mail, "mail");

			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Send mail") {
					/* Send mail
					 */
					if ($this->model->send_oke($_POST) == false) {
						$this->write_mail($_POST);
					} else if ($this->model->send_mail($_POST) == false) {
						$this->output->add_message("Error sending mail.");
						$this->write_mail($_POST);
					} else {
						$this->output->add_system_message("Mail has been sent.");
						$this->show_inbox();
						$this->user->log_action("mail %d sent to %d", $this->db->last_insert_id, $_POST["to_user_id"]);
					}
				} else if ($_POST["submit_button"] == "Delete mail") {
					/* Delete mail
					 */
					if (($mail = $this->model->get_mail($_POST["id"])) === false) {	
						$this->output->add_system_warning("Unknown mail");
					} else if ($this->model->delete_mail($_POST["id"]) == false) {
						$this->output->add_system_warning("Error deleting mail.");
					} else {
						$this->user->log_action("mail %d deleted", $_POST["id"]);
					}

					if ($mail["to_user_id"] == $this->user->id) {
						$this->show_inbox();
					} else {
						$this->show_sentbox();
					}
				}
			} else if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Show mail message
				 */
				if (($mail = $this->model->get_mail($this->page->pathinfo[1])) == false) {
					$this->output->add_tag("result", "Mail not found.");
				} else {
					$this->show_mail($mail);
				}
			} else if ($this->page->pathinfo[1] == "new") {
				/* New mail
				 */
				$mail = array();
				$this->write_mail($mail);
			} else if ($this->page->pathinfo[1] == "reply") {
				/* Reply
				 */
				if (($mail = $this->model->get_reply_mail($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "Error replying to mail.");
				} else {
					$this->write_mail($mail);
				}
			} else if ($this->page->pathinfo[1] == "sent") {
				/* Show sentbox
				 */
				$this->show_sentbox();
			} else {
				/* Show mailbox
				 */
				$this->show_inbox();
			}

			$this->output->title = $this->title;
			$this->output->add_tag("title", $this->title);
		}
	}
?>
