<?php
	class dictionary_controller extends controller {
		private function show_letters($letters, $first_letter) {
			$this->output->open_tag("letters", array("selected" => $first_letter));
			foreach ($letters as $letter) {
				$this->output->add_tag("letter", $letter["char"]);
			}
			$this->output->close_tag();
		}

		public function execute() {
			if (($letters = $this->model->get_first_letters()) === false) {	
				$this->output->add_tag("result", "Database error");
				return;
			}

			$this->output->description = "Dictionary";

			if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Show word
				 */
				if (($word = $this->model->get_word($this->page->pathinfo[1])) == false) {
					$this->output->add_tag("result", "Unknown word");
					return;
				}

				$this->output->keywords = $word["word"].", dictionary";
				$this->output->title = $word["word"]." - Dictionary";

				$first_letter = strtolower(substr($word["word"], 0, 1));

				$this->output->open_tag("word");
				$this->show_letters($letters, $first_letter);
				$this->output->record($word, "word");
				$this->output->close_tag();
			} else {
				/* Show overview
				 */
				$this->output->keywords = "dictionary";
				$this->output->title = "Dictionary";

				if (valid_input($this->page->pathinfo[1], VALIDATE_NONCAPITALS, 1) == false) {
					$first_letter = $letters[0]["char"];
				} else {
					$first_letter = $this->page->pathinfo[1];
				}

				if (($words = $this->model->get_words($first_letter)) === false) {
					$this->output->add_tag("result", "Database error.");
					return;
				}

				$this->output->open_tag("overview");
				$this->show_letters($letters, $first_letter);
				$this->output->open_tag("words");
				foreach ($words as $word) {
					$this->output->record($word, "word");
				}
				$this->output->close_tag();
				$this->output->close_tag();
			}
		}
	}
?>
