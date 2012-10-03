<?php
	class news_model extends model {
		public function count_news() {
			$query = "select count(*) as count from news";

			if (($result = $this->db->execute($query)) === false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_news($offset, $limit) {
			$query = "select *, UNIX_TIMESTAMP(timestamp) as timestamp ".
					 "from news order by timestamp desc limit %d,%d";

			return $this->db->execute($query, $offset, $limit);
		}

		public function get_news_item($id) {
			return $this->db->entry("news", $id);
		}
	}
?>
