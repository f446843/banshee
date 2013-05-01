<?php
	class collection_model extends model {
		public function get_collections() {
			$query = "select * from collections order by %S";

			return $this->db->execute($query, "name");
		}

		public function get_collection($id) {
			global $photo_extensions;

			$query = "select name from collections where id=%d";
			if (($result = $this->db->execute($query, $id)) == false) {
				return false;
			}
			$collection = $result[0];

			$query = "select a.* from photo_albums a, collection_album k ".
			         "where a.id=k.album_id and k.collection_id=%d";

			if (($collection["albums"] = $this->db->execute($query, $id)) === false) {
				return false;
			}

			$query = "select id,extension from photos ".
			         "where photo_album_id=%d and overview=%d";
			foreach ($collection["albums"] as &$album) {
				if (($photos = $this->db->execute($query, $album["id"], YES)) === false) {
					return false;
				}
				$photo = $photos[rand(0, count($photos) - 1)];

				$album["photo_id"] = $photo["id"];
				$album["extension"] = $photo["extension"];

				unset($album);
			}

			return $collection;
		}
	}
?>
