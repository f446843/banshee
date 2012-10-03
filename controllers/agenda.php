<?php
	class agenda_controller extends controller {
		private function fix_time($time) {
			$parts = explode(":", $time);
			return $parts[0].":".$parts[1];
		}

		private function show_month($month, $year) {
			global $days_of_week, $months_of_year;

			if (($appointments = $this->model->get_appointments_for_month($month, $year)) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$day = $this->model->monday_before($month, $year);
			$last_day = $this->model->sunday_after($month, $year);
			$today = strtotime("today 00:00:00");

			$this->output->open_tag("month", array("title" => $months_of_year[$month - 1]." ".$year));

			/* Links
			 */
			$y = $year;
			if (($m = $month - 1) == 0) {
				$m = 12;
				$y--;
			}
			$this->output->add_tag("prev", $y."/".$m);

			$y = $year;
			if (($m = $month + 1) == 13) {
				$m = 1;
				$y++;
			}
			$this->output->add_tag("next", $y."/".$m);

			/* Days of week
			 */
			$this->output->open_tag("days_of_week");
			foreach ($days_of_week as $dow) {
				$this->output->add_tag("day", $dow);
			}
			$this->output->close_tag();

			/* Weeks
			 */
			while ($day < $last_day) {
				$this->output->open_tag("week");
				for ($dow = 1; $dow <= 7; $dow++) {
					$params = array("nr" => date("j", $day), "dow" => $dow);
					if ($day == $today) {
						$params["today"] = " today";
					}
					$this->output->open_tag("day", $params);

					foreach ($appointments as $appointment) {
						if (($appointment["begin"] >= $day) && ($appointment["begin"] < $day + DAY)) {
							$begin_time = date("H:i:s", $appointment["begin"]);
							$end_time = date("H:i:s", $appointment["end"]);
							if (($begin_time != "00:00:00") || ($end_time != "23:59:59")) {
								$appointment["title"] = date("H:i ", $appointment["begin"]).$appointment["title"];
							}
							$this->output->add_tag("appointment", $appointment["title"], array("id" => $appointment["id"]));
						} else if (($appointment["begin"] < $day) && ($appointment["end"] >= $day)) {
							$begin_time = date("H:i:s", $appointment["begin"]);
							$end_time = date("H:i:s", $appointment["end"]);
							$this->output->add_tag("appointment", "... ".$appointment["title"], array("id" => $appointment["id"]));
						}
					}
					$this->output->close_tag();

					$day = strtotime(date("d-m-Y H:i:s", $day)." +1 day");
				}
				$this->output->close_tag();
			}
			$this->output->close_tag();
		}

		private function show_appointment($appointment_id) {
			global $months_of_year;

			if (($appointment = $this->model->get_appointment($appointment_id)) == false) {
				$this->output->add_tag("result", "Unknown appointment.");
				return;
			}

			$this->output->title = $appointment["title"]." - Agenda";

			$this->show_appointment_record($appointment);
		}

		private function show_appointment_record($appointment) {
			$appointment["begin_date"] = date("l j F Y", $appointment["begin"]);
			$appointment["begin_time"] = date("H:i", $appointment["begin"]);
			$begin_time = date("H:i:s", $appointment["begin"]);
			unset($appointment["begin"]);

			$appointment["end_date"] = date("l j F Y", $appointment["end"]);
			$appointment["end_time"] = date("H:i", $appointment["end"]);
			$end_time = date("H:i:s", $appointment["end"]);
			unset($appointment["end"]);

			$appointment["all_day"] = show_boolean(($begin_time == "00:00:00") && ($end_time == "23:59:59"));

			$this->output->record($appointment, "appointment");
		}

		public function execute() {
			$this->output->description = "Agenda";
			$this->output->keywords = "agenda";
			$this->output->title = "Agenda";

			if (isset($_SESSION["calendar_month"]) == false) {
				$_SESSION["calendar_month"] = (int)date("m");
				$_SESSION["calendar_year"]  = (int)date("Y");
			}

			if ($this->page->pathinfo[1] == "list") {
				/* Show appointment list
				 */
				if (($appointments = $this->model->get_appointments_from_today()) === false) {
					$this->output->add_tag("result", "Database error.");
				} else {
					$this->output->add_javascript("jquery/jquery.js");

					$this->output->open_tag("list");
					foreach ($appointments as $appointment) {
						$this->show_appointment_record($appointment);
					}
					$this->output->close_tag();
				}
			} else if ($this->page->pathinfo[1] == "current") {
				/* Show current month
				 */
				$_SESSION["calendar_month"] = (int)date("m");
				$_SESSION["calendar_year"]  = (int)date("Y");
				$this->show_month($_SESSION["calendar_month"], $_SESSION["calendar_year"]);
			} else if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
					$m = (int)$this->page->pathinfo[2];
					$y = (int)$this->page->pathinfo[1];

					if (($m >= 1) && ($m <= 12) && ($y > 1902) && ($y <= 2037)) {
						$_SESSION["calendar_month"] = $m;
						$_SESSION["calendar_year"]  = $y;
					}
					$this->show_month($_SESSION["calendar_month"], $_SESSION["calendar_year"]);
				} else {
					/* Show appointment
					 */
					$this->show_appointment($this->page->pathinfo[1]);
				}
			} else {
				/* Show month
				 */
				$this->show_month($_SESSION["calendar_month"], $_SESSION["calendar_year"]);
			}
		}
	}
?>
