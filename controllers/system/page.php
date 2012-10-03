<?php
	class system_page_controller extends controller {
		public function execute() {
			if (($page = $this->model->get_page($this->page->url)) == false) {
				$this->output->add_tag("website_error", 500);
				return;
			}

			/* Page header
			 */
			if (trim($page["description"]) != "") {	
				$this->output->description = $page["description"];
			}
			if (trim($page["keywords"]) != "") {
				$this->output->keywords = $page["keywords"];
			}
			$this->output->title = $page["title"];
			if ($page["style"] != null) {
				$this->output->inline_css = $page["style"];
			}
			$this->output->language = $page["language"];

			$this->output->set_layout($page["layout"]);

			if (($this->settings->hiawatha_cache_time > 0) && (isset($_SESSION["user_switch"]) == false) && is_false(DEBUG_MODE)) {
				header("X-Hiawatha-Cache: ".$this->settings->hiawatha_cache_time);
			}

			/* Page content
			 */
			$this->output->open_tag("page");
			$this->output->add_tag("title", $page["title"]);
			$this->output->add_tag("content", $page["content"]);
			if (is_true($page["back"])) {
				$parts = explode("/", $this->page->page);
				array_pop($parts);
				$this->output->add_tag("back", implode("/", $parts));
			}
			$this->output->close_tag();
		}
	}
?>
