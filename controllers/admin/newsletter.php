<?php
	class admin_newsletter_controller extends controller {
		private function start_writing() {
			$newsletter = array();
			$this->show_newsletter_form($newsletter);
		}

		private function show_newsletter_form($newsletter) {
			$this->output->record($newsletter, "newsletter");
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				/* Send newsletter
				 */
				if ($this->model->newsletter_oke($_POST) == false) {
					$this->show_newsletter_form($_POST);
				} else if ($_POST["submit_button"] == "Send newsletter") {
					if ($this->model->send_newsletter($_POST) == false) {
						$this->output->add_message("Error while sending newsletter.");
						$this->show_newsletter_form($_POST);
					} else {
						$this->output->add_tag("result", "Newsletter has been sent.");
					}
				} else if ($_POST["submit_button"] == "Preview newsletter") {
					if ($this->model->preview_newsletter($_POST) == false) {
						$this->output->add_message("Error while sending newsletter preview.");
					} else {
						$this->output->add_system_message("Newsletter preview has been sent.");
					}
					$this->show_newsletter_form($_POST);
				} else {
					$this->start_writing();
				}
			} else {
				/* Show newsletter form
				 */
				$this->start_writing();
			}
		}
	}
?>
