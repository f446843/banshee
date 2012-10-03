<?php
	class demos_posting_controller extends controller {
		public function execute() {
			$this->output->title = "Posting library demo";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$input = $_POST["input"];

				if (message_is_spam($input) == false) {
					$output = $input;
					$output = unescaped_output($output);
					$output = translate_bbcodes($output);
					$output = translate_smilies($output);

					$this->output->add_tag("input", $input);
					$this->output->add_tag("output", $output);
				} else {
					$this->output->add_message("Message seen as spam.");
				}
			}
		}
	}
?>
