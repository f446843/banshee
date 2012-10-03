<?php
	class demos_parameter_controller extends controller {
		public function execute() {
			$this->output->title = "Parameter inside URL";

			$this->output->add_tag("parameter", $this->page->pathinfo[1]);
		}
	}
?>
