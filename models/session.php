<?php
	class session_model extends model {
		public function get_sessions() {
			$query = "select id, session_id, UNIX_TIMESTAMP(expire) as expire , ip_address, name from sessions ".
			         "where user_id=%d and expire>=now() order by name, ip_address";

			return $this->db->execute($query, $this->user->id);
		}

		public function get_session($id) {
			$query = "select id, UNIX_TIMESTAMP(expire) as expire, ip_address, name ".
			         "from sessions where id=%d and user_id=%d and expire>=now()";

			if (($result = $this->db->execute($query, $id, $this->user->id)) == false) {
				return false;
			}

			return $result[0];
		}

		public function update_session($session) {
			$query = "update sessions set name=%s where id=%d and user_id=%d";

			return $this->db->execute($query, $session["name"], $session["id"], $this->user->id);
		}

		public function delete_session($id) {
			$query = "delete from sessions where id=%d and user_id=%d";

			return $this->db->query($query, $id, $this->user->id) !== false;
		}
	}
?>
