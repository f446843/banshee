<?php
	class admin_photos_controller extends tablemanager_controller {
		protected $name = "Photo";
		protected $pathinfo_offset = 2;
		protected $back = "admin";
		protected $icon = "photos.png";
		protected $page_size = null;
		protected $pagination_links = 7;
		protected $pagination_step = 1;
		protected $foreign_null = "---";
		protected $browsing = null;

		protected function show_overview() {
			$albums = $this->model->get_albums();

			$this->output->open_tag("albums", array("current" => $_SESSION["photo_album"]));
			foreach ($albums as $album) {
				$label = $album["name"].", ".date("d M Y", $album["timestamp"]);
				$this->output->add_tag("album", $label, array("id" => $album["id"]));
			}
			$this->output->close_tag();

			parent::show_overview();
		}

		protected function show_item_form($item) {
			if (isset($item["id"])) {
				if (isset($item["extension"]) == false) {
					if (($current = $this->model->get_item($item["id"])) != false) {
						$item["extension"] = $current["extension"];
					}
				}
				$this->output->add_tag("show_photo", $item["extension"], array("id" => $item["id"]));
			}
			parent::show_item_form($item);
		}

		public function execute() {
			$this->page_size = $this->settings->admin_page_size;

			/* Work-around for the most fucking annoying crap browser in the world: IE
			 */
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				foreach ($_FILES as $i => $file) {
					if ($file["type"] == "image/pjpeg") {
						$files[$i]["type"] = "image/jpeg";
					}
				}

				if (($_POST["title"] == "") && isset($_POST["photo_album_id"])) {
					if (($count = $this->model->count_photos_in_album($_POST["photo_album_id"])) !== false) {
						$_POST["title"] = "Photo ".($count + 1);
					}
				}
			}

			if (isset($_SESSION["photo_album"]) == false) {
				if (($albums = $this->model->get_albums()) != false) {
					$_SESSION["photo_album"] = $albums[0]["id"];
				}
			}

			if (($_SERVER["REQUEST_METHOD"] == "POST") && ($_POST["submit_button"] == "album")) {
				$_SESSION["photo_album"] = $_POST["album"];
				$_SERVER["REQUEST_METHOD"] = "GET";
			}

			$this->model->set_photo_album($_SESSION["photo_album"]);

			if (($album_count = $this->model->count_albums()) === false) {
				$this->output->open_tag("tablemanager");
				$this->output->add_tag("name", $this->name);
				$this->output->add_tag("result", "Error counting albums");
				$this->output->close_tag();
			} else if ($album_count == 0) {
				$this->output->open_tag("tablemanager");
				$this->output->add_tag("name", $this->name);
				$this->output->add_tag("result", "No albums have been created. Click <a href=\"/admin/albums\">here</a> to create a new photo album.");
				$this->output->close_tag();
			} else {
				parent::execute();
			}
		}
	}
?>
