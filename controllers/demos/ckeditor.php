<?php
	class demos_ckeditor_controller extends controller {
		public function execute() {
			$this->output->title = "CKEditor demo";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$this->output->open_tag("result");
				$this->output->add_tag("editor", $_POST["editor"]);
				$this->output->close_tag();
			} else {
				$this->output->add_javascript("ckeditor/ckeditor.js");
				$this->output->add_javascript("demos/ckeditor.js");
				$this->output->onload_javascript("start_ckeditor()");

				$this->output->add_tag("edit");
			}
		}
	}
?>
