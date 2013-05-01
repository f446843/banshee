<?php
	class session_controller extends controller {
		private function show_sessions() {
			if (($sessions = $this->model->get_sessions()) === false) {
				$this->output->add_tag("result", "Error fetching session information.");
				return;
			}

			$this->output->open_tag("sessions");
			foreach ($sessions as $session) {
				$session["owner"] = ($session["session_id"] == $_COOKIE[SESSION_NAME]) ? "current" : "other";
				$session["expire"] = date("j F Y, H:i:s", $session["expire"]);
				$this->output->record($session, "session");
			}
			$this->output->close_tag();
		}

		private function show_session_form($session) {
			$this->output->open_tag("edit");

			$this->output->record($session, "session");

			$this->output->close_tag();
		}

		public function execute() {
			if ($this->user->logged_in == false) {
				$this->output->add_tag("result", "The session manager should not be accessible for non-authenticated visitors!");
				return;
			} else if ($this->user->session_via_database == false) {
				$this->output->add_tag("result", "The database is not being used to store sessions, so there is nothing to manage.");
				return;
			}

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Update session") {
					/* Edit session
				 	 */
					if ($this->model->update_session($_POST) == false) {
						$this->output->add_tag("result", "Error while updateing session.");
					} else {
						$this->show_sessions();
					}
				} else if ($_POST["submit_button"] == "Delete session") {
					/* Delete session
					 */
					if ($this->model->delete_session($_POST["id"]) == false) {
						$this->output->add_tag("result", "Error while deleting session.");
					} else {
						$this->show_sessions();
					}
				} else {
					$this->show_sessions();
				}
			} else if (isset($this->page->pathinfo[1])) {
				/* Edit session
				 */
				if (($session = $this->model->get_session($this->page->pathinfo[1])) == false) {
					$this->output->add_tag("result", "Session not found.");
				} else {
					$session["expire"] = date("j F Y, H:i:s", $session["expire"]);
					$this->show_session_form($session);
				}
			} else {
				/* Show overview
				 */
				$this->show_sessions();
			}
		}
	}
?>
