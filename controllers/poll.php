<?php
	class poll_controller extends controller {
		public function execute() {
			$this->output->description = "Poll";
			$this->output->keywords = "poll";

			if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Show poll
				 */
				if (($poll = $this->model->get_poll($this->page->pathinfo[1])) == false) {
					$this->output->add_tag("result", "Poll not found");
				} else {
					$this->output->title = $poll["question"]." - Poll";

					$this->output->open_tag("poll", array("id" => $poll["id"]));
					$this->output->add_tag("question", $poll["question"]);

					$votes = 0;
					foreach ($poll["answers"] as $answer) {
						$votes += (int)$answer["votes"];
					}

					$this->output->open_tag("answers", array("votes" => $votes));
					foreach ($poll["answers"] as $answer) {
						unset($answer["poll_id"]);
						$answer["percentage"] = ($votes > 0) ? round(100 * (int)$answer["votes"] / $votes) : 0;
						$this->output->record($answer, "answer");
					}
					$this->output->close_tag();

					$this->output->close_tag();
				}
			} else {
				/* Poll overview
				 */
				$this->output->title = "Poll";

				if (($polls = $this->model->get_polls()) === false) {
					$this->output->add_tag("result", "Database error");
				} else {
					$active_poll_id = $this->model->get_active_poll_id();

					$this->output->open_tag("polls");
					foreach ($polls as $poll) {
						if ($poll["id"] != $active_poll_id) {
							$this->output->add_tag("question", $poll["question"], array("id" => $poll["id"]));
						}
					}
					$this->output->close_tag();
				}
			}
		}
	}
?>
