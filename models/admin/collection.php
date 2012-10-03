<?php
	class admin_collection_model extends model {
		public function get_collections() {
			$query = "select * from collections order by name";

			return $this->db->execute($query);
		}

		public function get_collection($collection_id) {
			$collection = $this->db->entry("collections", $collection_id);

			$query = "select p.id, p.name from photo_albums p, collection_album k ".
			         "where p.id=k.album_id and k.collection_id=%d";
			if (($albums = $this->db->execute($query, $collection_id)) === false) {
				return false;
			}

			$collection["albums"] = array();
			foreach ($albums as $album) {
				array_push($collection["albums"], $album["id"]);
			}

			return $collection;
		}

		public function get_albums() {
			$query = "select * from photo_albums order by %S";

			return $this->db->execute($query, "name");
		}

		public function save_oke($collection) {
			$result = true;

			if (trim($collection["name"]) == "") {
				$this->output->add_message("Name cannot be empty.");
				$result = false;
			}

			return $result;
		}

		private function set_collection_albums($collection_id, $albums) {
			if (is_array($albums) == false) {
				return true;
			}

			foreach ($albums as $album) {
				$data = array(
					"collection_id" => $collection_id,
					"album_id"      => $album["id"]);
				if ($this->db->insert("collection_album", $data) === false) {
					return false;
				}
			}

			return true;
		}

		public function create_collection($collection) {
			$keys = array("id", "name");

			$collection["id"] = null;

			$this->db->query("begin");

			if ($this->db->insert("collections", $collection, $keys) === false) {
				$this->db->query("rollback");
				return false;
			}

			if ($this->set_collection_albums($this->db->last_insert_id, $collection["albums"]) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") !== false;
		}

		public function update_collection($collection) {
			$keys = array("name");

			$this->db->query("begin");

			if ($this->db->update("collections", $collection["id"], $collection, $keys) === false) {
				$this->db->query("rollback");
				return false;
			}

			$query = "delete from collection_album where collection_id=%d";
			if ($this->db->query($query, $collection["id"]) === false) {
				$this->db->query("rollback");
				return false;
			}

			if ($this->set_collection_albums($collection["id"], $collection["albums"]) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") !== false;
		}

		public function delete_collection($collection_id) {
			$queries = array(
				array("delete from collection_album where collection_id=%d", $collection_id),
				array("delete from collections where id=%d", $collection_id));

			return $this->db->transaction($queries);
		}
	}
?>
