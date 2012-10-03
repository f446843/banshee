<?php
	class admin_albums_model extends tablemanager_model {
		protected $table = "photo_albums";
		protected $elements = array(
			"name" => array(
				"label"    => "Name",
				"type"     => "varchar",
				"overview" => true,
				"required" => true),
			"description" => array(
				"label"    => "Description",
				"type"     => "text",
				"overview" => false,
				"required" => true),
			"timestamp" => array(
				"label"    => "Timestamp",
				"type"     => "datetime",
				"overview" => true,
				"readonly" => true));

		public function delete_oke($item_id) {
			$query = "select count(*) as count from photos where photo_album_id=%d";

			if (($result = $this->db->execute($query, $item_id)) === false) {
				$this->output->add_message("Error counting photos in album.");
				return false;
			} else if ($result[0]["count"] > 0) {
				$this->output->add_message("Photo album contains photos. Delete them first.");
				return false;
			}

			return true;
		}

		public function delete_item($item_id) {
			$query = "select * from photos where photo_album_id=%d";
			if (($photos = $this->db->execute($query, $item_id)) === false) {
				return false;
			}

			$queries = array(
				array("delete from photos where photo_album_id=%d", $item_id),
				array("delete from collection_album where album_id=%d", $item_id),
				array("delete from %S where id=%d", $this->table, $item_id));

			if ($this->db->transaction($queries) == false) {
				return false;
			}

			foreach ($photos as $photo) {
				unlink(PHOTO_PATH."/image_".$photo["id"].".".$photo["extension"]);
				unlink(PHOTO_PATH."/thumbnail_".$photo["id"].".".$photo["extension"]);
			}

			return true;
		}
	}
?>
