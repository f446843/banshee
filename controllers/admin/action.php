<?php
	class admin_action_controller extends controller {
		public function execute() {
			if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY) == false) {
				$offset = 0;
			} else {
				$offset = $this->page->pathinfo[2];
			}

			if (isset($_SESSION["admin_actionlog_size"]) == false) {
				$_SESSION["admin_actionlog_size"] = $this->model->get_log_size();
			}

			$paging = new pagination($this->output, "admin_actionlog", $this->settings->admin_page_size, $_SESSION["admin_actionlog_size"]);

			if (($log = $this->model->get_action_log($paging->offset, $paging->size)) === false) {
				$this->output->add_tag("result", "Error reading action log.");
				return;
			}

			$users = array($this->user->id => $this->user->username);

			$this->output->open_tag("log");

			$this->output->open_tag("list");
			foreach ($log as $entry) {
				$user_id = $entry["user_id"];

				list($user_id, $switch_id) = explode(":", $user_id);

				if (isset($users[$user_id]) == false) {
					if (($user = $this->model->get_user($user_id)) !== false) {
						$users[$user_id] = $user["username"];
					}
				}

				if (isset($users[$switch_id]) == false) {
					if (($switch = $this->model->get_user($switch_id)) !== false) {
						$users[$switch_id] = $switch["username"];
					}
				}

				$entry["username"] = isset($users[$user_id]) ? $users[$user_id] : "-";
				$entry["switch"] = isset($users[$switch_id]) ? $users[$switch_id] : "-";

				$this->output->record($entry, "entry");
			}
			$this->output->close_tag();

			$paging->show_browse_links();

			$this->output->close_tag();
		}
	}
?>
