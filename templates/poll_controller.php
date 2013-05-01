<?php
	class demos_poll_controller extends controller {
		public function execute() {
			$poll = new poll($this->db, $this->output);

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$poll->vote($_POST["vote"]);
			}

			$poll->to_output();
		}
	}
?>
