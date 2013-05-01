<?php
	class banshee_page_model extends model {
		public function get_page($url) {
			return $this->db->entry("pages", $url, "url");
		}

		public function get_blocks($page_id) {
			$query = "select * from page_blocks where page_id=%d ".
			         "order by %S,%S";

			return $this->db->execute($query, $page_id, "position", "order");
		}
	}
?>
