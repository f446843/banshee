<?php
	class links_model extends model {
		public function get_links() {
			$query = "select * from links order by text";

			return $this->db->execute($query);
		}
	}
?>
