<?php
	class admin_guestbook_model extends model {
		public function count_messages() {
			$query = "select count(*) as count from guestbook";

			if (($result = $this->db->execute($query)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_messages($offset, $count) {
			$query = "select *, UNIX_TIMESTAMP(timestamp) as timestamp ".
					 "from guestbook order by timestamp desc limit %d,%d";

			return $this->db->execute($query, $offset, $count);
		}

		public function delete_message($message_id) {
			$this->db->delete("guestbook", $message_id);
		}
	}
?>
