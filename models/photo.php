<?php
	class photo_model extends model {
		public function count_albums() {
			$query = "select count(*) as count from photo_albums";

			if (($result = $this->db->execute($query)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_albums($offset, $limit) {
			$query = "select * from photo_albums order by timestamp desc limit %d,%d";

			if (($albums = $this->db->execute($query, $offset, $limit)) === false) {
				return false;
			}

			$query = "select * from photos where photo_album_id=%d and overview=%d";
			foreach ($albums as &$album) {
				if (($thumbnails = $this->db->execute($query, $album["id"], YES)) == false) {
					continue;
				}

				$photo = rand(0, count($thumbnails) - 1);
				$album["extension"] = $thumbnails[$photo]["extension"];
				$album["thumbnail"] = $thumbnails[$photo]["id"];
			}

			return $albums;
		}

		public function count_photos_in_album($album_id) {
			$query = "select count(*) as count from photos where photo_album_id=%d";

			if (($result = $this->db->execute($query, $album_id)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_album_info($album_id) {
			$query = "select * from photo_albums where id=%d";

			if (($result = $this->db->execute($query, $album_id)) == false) {
				return false;
			}

			return $result[0];
		}

		public function get_photo_info($album_id, $offset, $limit) {
			$query = "select * from photos where photo_album_id=%d limit %d,%d";

			return $this->db->execute($query, $album_id, $offset, $limit);
		}
	}
?>
