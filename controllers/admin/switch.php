<?php
	class admin_switch_controller extends controller {
		public function execute() {
			if (isset($_SESSION["user_switch"])) {
				/* User switch already active
				 */
				$this->output->add_tag("result", "User switch already active.", array("url" => $this->settings->start_page));
			} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
				/* Switch user
				 */
				if ($_POST["user_id"] == $this->user->id) {
					$this->output->add_tag("result", "Can't change to yourself.");
				} else if (($_POST["user_id"] == "0") || ($this->model->get_user($_POST["user_id"]) === false)) {
					$this->output->add_tag("result", "User doesn't exist.");
				} else {
					$this->user->log_action("switched to user_id %d", $_POST["user_id"]);
					$_SESSION["user_switch"] = $_SESSION["user_id"];
					$_SESSION["user_id"] = (int)$_POST["user_id"];
					$this->output->add_tag("result", "User switch successfull.", array("url" => $this->settings->start_page));
				}
			} else {
				/* Show user list
				 */
				if (($users = $this->model->get_users()) === false) {
					$this->output->add_tag("result", "Database error");
				} else {
					$this->output->open_tag("users");
					foreach ($users as $user) {
						$this->output->record($user, "user");
					}
					$this->output->close_tag();
				}
			}
		}
	}
?>
