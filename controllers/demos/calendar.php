<?php
	class demos_calendar_controller extends controller {
		public function execute() {
			$this->output->title = "Calendar demo";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$this->output->add_tag("result", $_POST["date"]);
			} else {
				$this->output->add_css("banshee/calendar.css");

				$this->output->add_javascript("banshee/calendar.js");
				$this->output->add_javascript("banshee/calendar-en.js");
				$this->output->add_javascript("banshee/calendar-setup.js");
				$this->output->add_javascript("demos/calendar.js");
				$this->output->run_javascript("setup_calendar()");

				$this->output->open_tag("edit");
				$this->output->add_tag("date", date("Y-m-d H:i:s"));
				$this->output->close_tag();
			}
		}
	}
?>
