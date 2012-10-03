<?php
	class login_controller extends controller {
		public function execute() {
			if ($this->user->logged_in == false) {
				$this->output->description = "Login";
				$this->output->keywords = "login";
				$this->output->title = "Login";

				$this->output->add_javascript(PASSWORD_HASH.".js");
				$this->output->add_javascript("login.js");
				$this->output->onload_javascript("set_focus(); hash = window['".PASSWORD_HASH."'];");

				$this->output->open_tag("login");

				if (($url = $_SERVER["REQUEST_URI"]) == "/".LOGIN_MODULE) {
					$url = "/".$this->settings->page_after_login;
				}
				$this->output->add_tag("url", $url);

				if ($_SERVER["REQUEST_METHOD"] != "POST") {
					$this->output->add_tag("bind");
				} else if (is_true($_POST["bind_ip"])) {
					$this->output->add_tag("bind");
				}

				$this->output->add_tag("remote_addr", $_SERVER["REMOTE_ADDR"]);
				$this->output->add_tag("challenge", $_SESSION["challenge"]);

				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$this->output->add_message("Login incorrect");
				}

				$this->output->close_tag();
			} else {
				$this->output->add_tag("result", "You are already logged in.", array("url" => $this->settings->page_after_login));
			}
		}
	}
?>
