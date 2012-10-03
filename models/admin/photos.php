<?php
	class admin_photos_model extends tablemanager_model {
		protected $table = "photos";
		protected $order = "title";
		protected $extensions = array(
			"image/gif"  => "gif",
			"image/jpeg" => "jpg",
			"image/png"  => "png");
		protected $elements = array(
			"title" => array(
				"label"    => "Title",
				"type"     => "varchar",
				"overview" => true,
				"required" => true),
			"photo_album_id" => array(
				"label"    => "Photo album",
				"type"     => "foreignkey",
				"table"    => "photo_albums",
				"column"   => "name",
				"overview" => false,
				"required" => true),
			"image" => array(
				"label"    => "Image",
				"type"     => "blob",
				"required" => true,
				"virtual"  => true),
			"extension" => array(
				"label"    => "Extension",
				"type"     => "varchar",
				"overview" => false,
				"readonly" => true),
			"overview" => array(
				"label"    => "Overview",
				"type"     => "boolean",
				"overview" => true,
				"required" => true));

		public function set_photo_album($id) {
			$this->elements["photo_album_id"]["default"] = $id;
		}

		public function count_albums() {
			$query = "select count(*) as count from photo_albums";

			if (($result = $this->db->execute($query)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function count_photos_in_album($album_id) {
			$query = "select count(*) as count from photos where photo_album_id=%d";
			if (($result = $this->db->execute($query, $album_id)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_albums() {
			$query = "select id,name,UNIX_TIMESTAMP(timestamp) as timestamp from photo_albums order by name";

			return $this->db->execute($query);
		}

		public function save_oke($item) {
			$result = parent::save_oke($item);

			$allowed_types = array_keys($this->extensions);
			if (isset($item["image"])) {
				if (in_array($_FILES["image"]["type"], $allowed_types) == false) {
					$this->output->add_message("Incorrect file type");
					$result = false;
				}
			}

			return $result;
		}

		public function count_items() {
			$query = "select count(*) as count from photos where photo_album_id=%d";

			if (($result = $this->db->execute($query, $_SESSION["photo_album"])) === false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_items() {
			$query = "select * from photos where photo_album_id=%d order by %S";

			return $this->db->execute($query, $_SESSION["photo_album"], $this->order);
		}

		private function save_image($item) {
			if (isset($item["image"]) == false) {
				return true;
			}

			switch ($item["extension"]) {
				case "gif": $image = new gif_image(); break;
				case "jpg": $image = new jpeg_image(); break;
				case "png": $image = new png_image(); break;
				default: return false;
			}

			$image->from_string($item["image"]);

			if (($image->width > $this->settings->photo_image_height) || ($image->height > $this->settings->photo_image_width)) {
				$image->resize($this->settings->photo_image_height, $this->settings->photo_image_width);
			}

			if ($image->save(PHOTO_PATH."/image_".$item["id"].".".$item["extension"]) == false) {
				return false;
			}
			unset($image);
			
			return true;
		}

		private function save_thumbnail(&$item) {
			if (isset($item["image"]) == false) {
				return true;
			}

			switch ($item["extension"]) {
				case "gif": $image = new gif_image(); break;
				case "jpg": $image = new jpeg_image(); break;
				case "png": $image = new png_image();	break;
				default: return false;
			}

			$image->from_string($item["image"]);
			$image->resize($this->settings->photo_thumbnail_height, $this->settings->photo_thumbnail_width);

			if ($image->save(PHOTO_PATH."/thumbnail_".$item["id"].".".$item["extension"]) == false) {
				return false;
			}
			unset($image);

			return true;
		}

		private function set_extension(&$item) {
			if (isset($_FILES["image"]) == false) {
				return true;
			} else if ($_FILES["image"]["error"] != 0) {
				return true;
			}

			$type = $_FILES["image"]["type"];
			if (isset($this->extensions[$type]) == false) {
				return false;
			}

			$item["extension"] = $this->extensions[$type];

			return true;
		}

		public function create_item($item) {
			if ($this->db->query("begin") == false) {
				return false;
			}

			$this->set_extension($item);
			if (parent::create_item($item) === false) {
				$this->db->query("rollback");
				return false;
			}
			$item["id"] = $this->db->last_insert_id;
			
			if ($this->save_image($item) == false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->save_thumbnail($item) == false) {
				$this->db->query("rollback");
				unlink(PHOTO_PATH."/image_".$item["id"].".".$item["extension"]);
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function update_item($item) {
			if (isset($item["image"])) {
				if (($photo = $this->get_item($item["id"])) == false) {
					return false;
				} else if (unlink(PHOTO_PATH."/image_".$item["id"].".".$photo["extension"]) == false) {
					return false;
				} else if (unlink(PHOTO_PATH."/thumbnail_".$item["id"].".".$photo["extension"]) == false) {
					return false;
				}

				$this->set_extension($item);
				$this->elements["extension"]["readonly"] = false;
			}

			if ($this->db->query("begin") == false) {
				return false;
			}

			if (parent::update_item($item) === false) {
				$this->db->query("rollback");
				return false;
			}

			if ($this->save_image($item) == false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->save_thumbnail($item) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function delete_item($item_id) {
			if (($photo = $this->get_item($item_id)) == false) {
				return false;
			}

			if ($this->db->query("begin") == false) {
				return false;
			}

			if (parent::delete_item($item_id) == false) {
				$this->db->query("rollback");
				return false;
			}

			if (unlink(PHOTO_PATH."/image_".$item_id.".".$photo["extension"]) == false) {
				$this->db->query("rollback");
				return false;
			} else if (unlink(PHOTO_PATH."/thumbnail_".$item_id.".".$photo["extension"]) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}
	}
?>
