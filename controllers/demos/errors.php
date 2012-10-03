<?php
	class demos_errors_controller extends controller {
		public function execute() {
			$this->output->title = "Error demo";

			print "These are error messages caused by PHP errors:\n";
			$result = 1 / 0;
			$result = substr();
		}
	}
?>
