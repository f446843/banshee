<?php
	class links_controller extends controller {
		public function execute() {
			$this->output->title = "Links";

			if (($links = $this->model->get_links()) === false) {
				$this->output->add_tag("result", "Database error.");
			} else {
				$this->output->open_tag("links");
				foreach ($links as $link) {
					$this->output->add_tag("link", $link["text"], array("url" => $link["link"]));
				}
				$this->output->close_tag();
			}
		}
	}
?>
