<?php
	class photo_controller extends controller {
		private $title = "Photos";
        private $extensions = array(
			"gif" => "image/gif",
			"jpg" => "image/jpeg",
			"png" => "image/png");

		private function show_albums() {
			$this->title = "Photo albums";

			if (($count = $this->model->count_albums()) === false) {
				$this->output->add_tag("result", "Database error counting albums");
				return;
			}

			$paging = new pagination($this->output, "photo_albums", $this->settings->photo_page_size, $count);

			if (($albums = $this->model->get_albums($paging->offset, $paging->size)) === false) {
				$this->output->add_tag("result", "Database error retrieving albums");
				return;
			} else if (count($albums) == 0) {
				$this->output->add_tag("result", "No photo albums have been created.");
				return;
			}

			$this->output->open_tag("overview");

			$this->output->open_tag("albums");
			foreach ($albums as $album) {
				$this->output->record($album, "album");
			}
			$this->output->close_tag();

			$paging->show_browse_links();

			$this->output->close_tag();
		}

		private function show_album($album_id) {
			if (($album = $this->model->get_album_info($album_id)) == false) {
				$this->output->add_tag("result", "Database error retrieving album title.");
				return;
			}

			if (($count = $this->model->count_photos_in_album($album_id)) === false) {
				$this->output->add_tag("result", "Database error counting albums");
				return;
			}

			$paging = new pagination($this->output, "photo_album_".$album_id, $this->settings->photo_album_size, $count);

			if (($photos = $this->model->get_photo_info($album_id, $paging->offset, $paging->size)) === false) {
				$this->output->add_tag("result", "Database error retrieving photos.");
				return;
			} else if (count($photos) == 0) {
				$this->output->add_tag("result", "Photo album is empty.");
				return;
			}

			$this->title = $album["name"];

			$this->output->open_tag("photos", array("info" => $album["description"]));
			foreach ($photos as $photo) {
				$this->output->record($photo, "photo");
			}
			$paging->show_browse_links();
			$this->output->close_tag();

			$this->output->add_javascript("jquery/jquery.js");
			$this->output->add_javascript("jquery/slimbox2.js");
			$this->output->add_javascript("photo.js");

			$this->output->add_css("includes/slimbox2.css");
		}

		private function show_photo($photo) {
			list($name, $extension) = explode(".", $photo);
			if (isset($this->extensions[$extension]) == false) {
				header("Result: 404");
				return;
			} else if (file_exists(PHOTO_PATH."/".$photo) == false) {
				header("Result: 404");
				return;
			}

			header("Content-Type: ".$this->extensions[$extension]);
			readfile(PHOTO_PATH."/".$photo);

			$this->output->disabled = true;
		}

		public function execute() {
			if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				$this->show_album($this->page->pathinfo[1]);
			} else if (valid_input($this->page->pathinfo[1], VALIDATE_NONCAPITALS.VALIDATE_NUMBERS."_.", VALIDATE_NONEMPTY)) {
				$this->show_photo($this->page->pathinfo[1]);
			} else {
				$this->show_albums();
			}

			$this->output->add_tag("title", $this->title);
			$this->output->title = $this->title;
		}
	}
?>
