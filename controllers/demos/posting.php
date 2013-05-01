<?php
	class demos_posting_controller extends controller {
		public function execute() {
			$this->output->title = "Posting library demo";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$input = $_POST["input"];

				$message = new message($input);
				if ($message->is_spam == false) {
					$message->unescaped_output();
					$message->translate_bbcodes();
					$message->translate_smilies();

					$this->output->add_tag("output", $message->content);
				} else {
					$this->output->add_message("Message seen as spam.");
				}

				$this->output->add_tag("input", $input);
			}
		}
	}
?>
