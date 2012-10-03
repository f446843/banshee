<?php
	class demos_ajax_controller extends controller {
		private function ajax_request() {
			if (isset($_REQUEST["answer"])) {
				$result = $_REQUEST["answer"] == 3 ? "correct" : "wrong";
				print "oke";
				$this->output->add_tag("result", $result);
			} else if (isset($_REQUEST["records"])) {
				if (($records = $_REQUEST["records"]) > 10) {
					$records = 10;
				}
				for ($i = 0; $i < $records; $i++) {
					$this->output->open_tag("vars");
					$vars = rand(1, 3);
					for ($v = 0; $v < $vars; $v++) {
						$this->output->add_tag("var", "value".rand(0, 9));
					}
					$this->output->close_tag();
				}
			} else if (isset($_REQUEST["text"])) {
				$this->output->add_tag("text", $_REQUEST["text"]);
			}
		}

		public function execute() {
			$this->output->title = "AJAX demo";

			if ($this->page->ajax_request) {
				$this->ajax_request();
				return;
			}

			$this->output->add_javascript("ajax.js");
			$this->output->add_javascript("demos/ajax.js");
			$this->output->onload_javascript("set_focus()");
		}
	}
?>
