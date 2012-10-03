<?php
	class demos_system_message_controller extends controller {
		public function execute() {
			$this->output->add_system_message("This is a system message.");
			$this->output->add_system_warning("This is a system warning.");
		}
	}
?>
