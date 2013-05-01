<?php
	class guestbook_controller extends controller {
		private function show_guestbook_form($message) {
			$this->output->record($message);
		}

		public function execute() {
			$this->output->description = "Guestbook";
			$this->output->keywords = "guestbook";
			$this->output->title = "Guestbook";
			$skip_sign_link = false;

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($this->model->message_oke($_POST) == false) {
					$this->show_guestbook_form($_POST);
				} else if ($this->model->save_message($_POST) == false) {
					$this->output->add_message("Database errors while saving message.");
					$this->show_guestbook_form($_POST);
				} else {
					$skip_sign_link = true;
				}
			}

			if (($message_count = $this->model->count_messages()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$paging = new pagination($this->output, "guestbook", $this->settings->guestbook_page_size, $message_count);

			if (($guestbook = $this->model->get_messages($paging->offset, $paging->size)) === false) {
				$this->output->add_tag("result", "Database error.");
			} else {
				$this->output->open_tag("guestbook", array("skip_sign_link" => show_boolean($skip_sign_link)));

				foreach ($guestbook as $item) {
					$item["timestamp"] = date("j F Y, H:i", $item["timestamp"]);
					$message = new message($item["message"]);
					$item["message"] = $message->unescaped_output();
					unset($item["ip_address"]);
					$this->output->record($item, "item");
				}

				$paging->show_browse_links(7, 3);

				$this->output->close_tag();
			}
		}
	}
?>
