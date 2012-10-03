<?php
	class rss_controller extends controller {
		public function execute() {
			if ($this->page->type == "xml") {
				/* RSS feed
				 */
				$rss = new RSS($this->output);
				if ($rss->fetch_from_cache("rss_cache_id") == false) {
					$rss->title = "RSS title";
					$rss->description = "RSS description";

					if (($items = $this->model->get_items()) != false) {
						foreach ($items as $item) {
							$rss->add_item($item["title"], $item["content"], $item["link"], $item["timestamp"]);
						}
						$rss->to_output();
					}
				}
			} else {
				/* Other page type
				 */
			}
		}
	}
?>
