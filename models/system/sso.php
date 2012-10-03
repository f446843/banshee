<?php
	class system_sso_model extends model {
		public function get_user_id($username) {
			if (($result = $this->db->entry("users", $username, "username")) == false) {
				return false;
			}

			return (int)$result["id"];
		}
	}
?>
