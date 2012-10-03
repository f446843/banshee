<?php
	class admin_action_model extends model {
		public function get_log_size() {
			if (($fp = fopen("../logfiles/actions.log", "r")) == false) {
				return false;
			}

			$count = 0;
			while (($line = fgets($fp)) != false) {
				$count++;
			}

			fclose($fp);

			return $count;
		}

		public function get_action_log($offset, $size) {
			if (($fp = fopen("../logfiles/actions.log", "r")) == false) {
				return false;
			}

			$count = 0;
			$log = array();
			while (($line = fgets($fp)) != false) {
				$entry = explode("|", chop($line));
				array_unshift($log, array(
					"ip"        => $entry[0],
					"timestamp" => $entry[1],
					"user_id"   => $entry[2],
					"event"     => $entry[3]));

				if ($count >= $size + $offset) {
					array_pop($log);
				} else {
					$count++;
				}
			}
			fclose($fp);

			return array_slice($log, $offset);
		}

		public function get_user($user_id) {
			return $this->db->entry("users", $user_id);
		}
	}
?>
