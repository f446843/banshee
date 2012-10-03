<?php
	class newsletter_controller extends controller {
		private function start_subscribing() {
			$info = array();
			$this->show_form($info);
		}

		private function show_form($info) {
			$this->output->onload_javascript("document.getElementById('email').focus()");

			$this->output->add_tag("subscribe", $info["email"]);
		}

		public function execute() {
			$this->output->title = "Newsletter";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				/* Newsletter form
				 */
				if ($this->model->info_oke($_POST) == false) {
					$this->show_form($_POST);
				} else if ($_POST["submit_button"] == "Subscribe") {
					if ($this->model->ask_confirmation($_POST, true) == false) {
						$this->output->add_tag("result", "Subscribe error.");
					} else {
						$this->output->add_tag("result", "If the supplied e-mail address is not already on the newsletter list, an e-mail with a confirmation code will be sent to the supplied e-mail address. Please note that this code is only valid for ".$this->settings->newsletter_code_timeout.".", array("seconds" => "10"));
					}
				} else if ($_POST["submit_button"] == "Unsubscribe") {
					if ($this->model->ask_confirmation($_POST, false) == false) {
						$this->output->add_tag("result", "Unsubscribe error.");
					} else {
						$this->output->add_tag("result", "If the supplied e-mail address is present on the newsletter list, an e-mail with a confirmation code will be sent to the supplied e-mail address. Please note that this code is only valid for ".$this->settings->newsletter_code_timeout.".", array("seconds" => "10"));
					}
				} else {	
					$this->start_subscribing();
				}
			} else if (isset($_GET["code"])) {
				/* (Un)subscribe to the newsletter
				 */
				if (isset($_GET["subscribe"])) {
					if ($this->model->verify_code($_GET["subscribe"], $_GET["code"]) == false) {
						$this->output->add_tag("result", "The supplied confirmation code is invalid.");
					} else if ($this->model->subscribe($_GET["subscribe"]) == false) {
						$this->output->add_tag("result", "Error while adding your e-mail address to the ".$this->settings->head_title." newsletter list.");
					} else {
						$this->output->add_tag("result", "Your e-mail address has been added to the ".$this->settings->head_title." newsletter list.", array("seconds" => 10));
					}
				} else if (isset($_GET["unsubscribe"])) {
					if ($this->model->verify_code($_GET["unsubscribe"], $_GET["code"]) == false) {
						$this->output->add_tag("result", "The supplied confirmation code is invalid.");
					} else if ($this->model->unsubscribe($_GET["unsubscribe"]) == false) {
						$this->output->add_tag("result", "Error while removing your e-mail address from the ".$this->settings->head_title." newsletter list.");
					} else {
						$this->output->add_tag("result", "Your e-mail address has been removed from the ".$this->settings->head_title." newsletter list.", array("seconds" => 10));
					}
				}
			} else {
				/* Show form
				 */
				$this->start_subscribing();
			}
		}
	}
?>
