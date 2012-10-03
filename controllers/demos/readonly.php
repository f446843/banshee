<?php
	class demos_readonly_controller extends controller {
		public function execute() {
			if ($this->page->write_access) {
				$this->output->add_tag("message", "This user has write access on this page.");
			} else {
				$this->output->add_tag("message", "This is a read-only page for this user.");
			}
		}
	}
?>
