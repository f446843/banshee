<?php
	class contact_controller extends controller {
		private function show_contact_form($contact) {
			$this->output->record($contact, "contact");
		}

		public function execute() {
			$this->output->description = "Contact page";
			$this->output->keywords = "contact";
			$this->output->title = "Contact";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				/* Send contact information
				 */
				if ($this->model->contact_oke($_POST) == false) {
					$this->show_contact_form($_POST);
				} else if ($this->model->send_contact($_POST) == false) {
					$this->output->add_message("Error while sending contact information.");
					$this->show_contact_form($_POST);
				} else {
					$this->output->add_tag("result", "Your contact information has been sent to the website owner.");
				}
			} else {
				/* Show contact form
				 */
				$contact = array();
				$this->show_contact_form($contact);
			}
		}
	}
?>
