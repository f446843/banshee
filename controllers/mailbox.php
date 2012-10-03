<?php
	class mailbox_controller extends controller {
		private function show_mails($mails) {
			$this->output->open_tag("mailbox");
			foreach ($mails as $mail) {
				$mail["subject"] = truncate_text($mail["subject"], 55);
				$mail["timestamp"] = date_string("l, j F Y H:i:s", $mail["timestamp"]);
				$mail["read"] = $mail["read"] == YES ? "read" : "unread";
				$this->output->record($mail, "mail");
			}
			$this->output->close_tag();
		}

		private function show_mailbox() {
			if (($mails = $this->model->get_mailbox()) === false) {
				$this->output->add_tag("result", "Error reading mailbox.");
			} else {
				$this->show_mails($mails);
				$this->output->add_tag("link", "Sentbox", array("url" => "/sent"));
			}
		}

		private function show_sentbox() {
			if (($mails = $this->model->get_sentbox()) === false) {
				$this->output->add_tag("result", "Error reading sentbox.");
			} else {
				$this->show_mails($mails);
				$this->output->add_tag("link", "Inbox", array("url" => ""));
			}
		}

		private function show_mail($mail) {
			$mail["message"] = unescaped_output($mail["message"]);
			$this->output->record($mail, "mail", array("actions" => show_boolean($mail["to_user_id"] == $this->user->id)));
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
						$this->show_mailbox();
					}
				} else if ($_POST["submit_button"] == "Delete mail") {
					/* Delete mail
					 */
					if ($this->model->delete_mail($_POST["id"]) == false) {
						$this->output->add_system_warning("Error deleting mail.");
					}
					$this->show_mailbox();
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
				$this->show_mailbox();
			}
		}
	}
?>
