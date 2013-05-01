<?php
	class XXX_model extends model {
		public function count_XXXs() {
			$query = "select count(*) as count from XXXs order by id";

			if (($result = $this->db->execute($query)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_XXXs($offset, $limit) {
			$query = "select * from XXXs order by id limit %d,%d";

			return $this->db->execute($query, $offset, $limit);
		}

		public function get_XXX($XXX_id) {
			return $this->db->entry("XXXs", $XXX_id);
		}

		public function save_oke($XXX) {
			$result = true;

			return $result;
		}

		public function create_XXX($XXX) {
			$keys = array("id", "xxx");

			$XXX["id"] = null;

			return $this->db->insert("XXXs", $XXX, $keys);
		}

		public function update_XXX($XXX) {
			$keys = array("xxx");

			return $this->db->update("XXXs", $XXX["id"], $XXX, $keys);
		}

		public function delete_oke($XXX) {
			$result = true;

			return $result;
		}

		public function delete_XXX($XXX_id) {
			return $this->db->delete("XXXs", $XXX_id);
		}
	}
?>
