<?php
	class collection_controller extends controller {
		private $title = "Photo album collections";

		private function show_collection_overview() {
			if (($collections = $this->model->get_collections()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$this->output->open_tag("overview");

			$this->output->open_tag("collections");
			foreach ($collections as $collection) {
				$this->output->record($collection, "collection");
			}
			$this->output->close_tag();

			$this->output->close_tag();
		}

		private function show_collection($collection) {
			$this->title = $collection["name"];

			$this->output->open_tag("collection");
			foreach ($collection["albums"] as $album) {
				$this->output->record($album, "album");
			}
			$this->output->close_tag();
		}

		public function execute() {
			if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY) == false) {
				$this->show_collection_overview();
			} else if (($collection = $this->model->get_collection($this->page->pathinfo[1])) == false) {
				$this->output->add_tag("result", "Collection not found.");
			} else {
				$this->show_collection($collection);
			}

			$this->output->add_tag("title", $this->title);
			$this->output->title = $this->title;
		}
	}
?>
