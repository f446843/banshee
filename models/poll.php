<?php
	class poll_model extends model {
		public function get_active_poll_id() {
			$query = "select *, UNIX_TIMESTAMP(begin) as begin, UNIX_TIMESTAMP(end) as end ".
					 "from polls where begin<=now() and end>now() order by begin desc limit 1";
			if (($result = $this->db->execute($query)) == false) {
				return false;
			}

			return $result[0]["id"];
		}

		public function get_polls() {
			$query = "select * from polls where begin<=now() order by begin desc";

			return $this->db->execute($query);
		}

		public function get_poll($poll_id) {
			if ($poll_id == $this->get_active_poll_id()) {
				return false;
			}

			if (($poll = $this->db->entry("polls", $poll_id)) == false) {
				return false;
			}

			if (strtotime($poll["begin"]) > time()) {
				return false;
			}

			$query = "select * from poll_answers where poll_id=%d";
			$poll["answers"] = $this->db->execute($query, $poll["id"]);

			return $poll;
		}
	}
?>
