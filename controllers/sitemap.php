<?php
	class sitemap_controller extends controller {
		public function execute() {
			$this->output->content_type = "application/xml";

			if ($this->output->fetch_from_cache("sitemap")) {
				return;
			}

			$this->output->start_caching("sitemap");

			$this->output->add_tag("protocol", $_SERVER["HTTP_SCHEME"]);
			$this->output->add_tag("hostname", $_SERVER["SERVER_NAME"]);

			$this->output->open_tag("urls");

			$urls = $this->model->get_public_urls();
			foreach ($urls as $url) {
				if (strpos($url, "*") !== false) {
					continue;
				}

				$this->output->open_tag("url");
				$this->output->add_tag("loc", $url);
				$this->output->close_tag();
			}
			$this->output->close_tag();

			$this->output->stop_caching();
		}
	}
?>
