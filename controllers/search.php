<?php
	class search_controller extends controller {
		private function ajax_request() {
			if (strlen($_GET["query"]) == 0) {
				return;
			} else if (($fp = fopen("../logfiles/search.log", "a")) == false) {
				return;
			}

			fputs($fp, $_SERVER["REMOTE_ADDR"]."|".date("Y-m-d H:i:s")."|".$_GET["query"]."\n");
			fclose($fp);
		}

		public function execute() {
			if ($this->page->ajax_request) {
				$this->ajax_request();
				return;
			}

			$this->output->title = "Search";

			$this->output->add_javascript("http://www.google.com/jsapi");
			$this->output->add_javascript("ajax.js");

			$this->output->add_tag("hostname", $_SERVER["SERVER_NAME"]);
		}
	}
?>
