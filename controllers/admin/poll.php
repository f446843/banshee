<?php
	class admin_poll_controller extends controller {
		private function show_poll_overview() {
			if (($polls = $this->model->get_polls()) === false) {
				$this->output->add_tag("result", "Database error");
			} else {
				$today = strtotime("today 00:00:00");

				$this->output->open_tag("overview");

				$this->output->open_tag("polls");
				foreach ($polls as $poll) {
					$edit = show_boolean($poll["begin"] > $today);
					$poll["begin"] = date("j F Y", $poll["begin"]);
					$poll["end"] = date("j F Y", $poll["end"]);
					$this->output->record($poll, "poll", array("edit" => $edit));
				}
				$this->output->close_tag();

				$this->output->close_tag();
			}
		}

		private function show_poll_form($poll) {
			if (isset($poll["id"]) == false) {
				$params = array();
			} else {
				$params = array("id" => $poll["id"]);
			}

			$this->output->add_javascript("calendar.js");
			$this->output->add_javascript("calendar-en.js");
			$this->output->add_javascript("calendar-setup.js");
			$this->output->add_javascript("admin/poll.js");
			$this->output->onload_javascript("setup_calendars()");

			$this->output->open_tag("edit");

			$this->output->open_tag("poll", $params);
			$this->output->add_tag("question", $poll["question"]);
			$this->output->add_tag("begin", $poll["begin"]);
			$this->output->add_tag("begin_show", date("j F Y", strtotime($poll["begin"])));
			$this->output->add_tag("end", $poll["end"]);
			$this->output->add_tag("end_show", date("j F Y", strtotime($poll["end"])));

			$this->output->open_tag("answers");
			for ($i = 0; $i < $this->settings->poll_max_answers; $i++) {
				$this->output->add_tag("answer", $poll["answers"][$i], array("nr" => $i + 1));
			}
			$this->output->close_tag();

			$this->output->close_tag();

			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Save poll") {
					/* Save poll
					 */
					if ($this->model->save_oke($_POST) == false) {
						$this->show_poll_form($_POST);
					} else {
						if (isset($_POST["id"]) == false) {
							/* Create poll
							 */
							if ($this->model->create_poll($_POST) == false) {
								$this->output->add_message("Error while creating poll.");
								$this->show_poll_form($_POST);
							} else {
								$this->user->log_action("poll %d created", $this->db->last_insert_id);
								$this->show_poll_overview();
							}
						} else {
							/* Update poll
							 */
							if ($this->model->update_poll($_POST) == false) {
								$this->output->add_message("Error while updating poll.");
								$this->show_poll_form($_POST);
							} else {
								$this->user->log_action("poll %d updated", $_POST["id"]);
								$this->show_poll_overview();
							}
						}
					}
				} else if ($_POST["submit_button"] == "Delete poll") {
					/* Delete poll
					 */
					if ($this->model->delete_poll($_POST["id"]) == false) {
						$this->output->add_tag("result", "Error while deleting poll.");
					} else {
						$this->user->log_action("poll %d deleted", $_POST["id"]);
						$this->show_poll_overview();
					}
				} else {
					$this->show_poll_overview();
				}
			} else if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Edit existing poll
				 */
				if (($poll = $this->model->get_poll($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "Poll not found or not available for editing.");
				} else {
					$this->show_poll_form($poll);
				}
			} else if ($this->page->pathinfo[2] == "new") {
				/* Create new poll
				 */
				$poll = array(
					"begin"   => date("Y-m-d"),
					"end"     => date("Y-m-d"),
					"answers" => array());
				$this->show_poll_form($poll);
			} else {
				/* Show poll overview
				 */
				$this->show_poll_overview();
			}
		}
	}
?>
