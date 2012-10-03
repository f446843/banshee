<?php
	class logout_controller extends controller {
		public function execute() {
			if ($this->user->logged_in) {
				$this->output->open_tag("logout");
				if (isset($_SESSION["user_switch"]) == false) {
					$this->user->logout();
				} else {
					$this->user->log_action("switched back to self");
					$_SESSION["user_id"] = $_SESSION["user_switch"];
					unset($_SESSION["user_switch"]);
				}
				$this->output->close_tag();
			} else {
				$this->output->add_tag("result", "You are not logged in.", array("url" => $this->settings->start_page));
			}
		}
	}
?>
