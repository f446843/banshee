<?php
	class demos_alphabetize_controller extends controller {
		public function execute() {
			$alphabetize = new alphabetize($this->output, "demo");
			$words = $this->model->get_words($alphabetize->char);

			$this->output->open_tag("words");
			foreach ($words as $word) {
				$this->output->add_tag("word", $word);
			}
			$this->output->close_tag();

			$alphabetize->show_browse_links();
		}
	}
?>
