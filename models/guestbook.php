<?php
	require_once("../helpers/anti_spam.php");

	class guestbook_model extends model {
		public function count_messages() {
			$query = "select count(*) as count from guestbook";

			if (($result = $this->db->execute($query)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_messages($offset, $limit) {
			$query = "select *, UNIX_TIMESTAMP(timestamp) as timestamp ".
			"from guestbook order by timestamp desc limit %d,%d";

			return $this->db->execute($query, $offset, $limit);
		}

		public function message_oke($message) {
			$result = true;

			if (trim($message["author"]) == "") {
				$this->output->add_message("Please, fill in your name.");
				$result = false;
			}
			if (trim($message["message"]) == "") {
				$this->output->add_message("Please, leave a message.");
				$result = false;
			}

			return $result;
		}

		public function save_message($message) {
			$keys = array("id", "author", "message", "timestamp", "ip_address");

			$message["id"] = null;
			$message["timestamp"] = null;
			$message["ip_address"] = $_SERVER["REMOTE_ADDR"];

			if ($this->db->insert("guestbook", $message, $keys) === false) {
				return false;
			}

			$this->send_notification($message["message"]);

			return true;
		}

		private function send_notification($message) {
			if ($this->settings->guestbook_maintainers == "") {
				return;
			}

			$maintainers = users_with_role($this->db, $this->settings->guestbook_maintainers);

			$guestbook_url = "http://".$_SERVER["SERVER_NAME"]."/".$this->page->module;

			$email = new email("Guestbook message posted", $this->settings->webmaster_email);

			foreach ($maintainers as $maintainer) {
				$cms_url = "http://".$_SERVER["SERVER_NAME"]."/admin/guestbook";
				if (($key = one_time_key($this->db, $maintainer["id"])) !== false) {
					$cms_url .= "?login=".$key;
				}

				$message =
					"<body>".
					"<p>The following message has been added to the guestbook on the '".$this->settings->head_title."' website.</p>".
					"<p>\"<i>".$message."</i>\"</p>".
					"<p>Click <a href=\"".$guestbook_url."\">here</a> to visit the guestbook page or <a href=\"".$cms_url."\">here</a> to visit the guestbook CMS page.</p>".
					"</body>";

				$email->message($message);
				$email->send($maintainer["email"], $maintainer["fullname"]);
			}
		}
	}
?>
