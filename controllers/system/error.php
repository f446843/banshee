<?php
	class system_error_controller extends controller {
		public function execute() {
			header("Status: ".$this->page->http_code);

			$this->output->add_tag("website_error", $this->page->http_code);
			$this->output->add_tag("webmaster_email", $this->settings->webmaster_email);
		}
	}
?>
