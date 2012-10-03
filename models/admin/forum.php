<?php
	class admin_forum_model extends model {
		public function count_messages() {
			$query = "select count(*) as count from forum_messages";

			if (($result = $this->db->execute($query)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_messages($offset, $count) {
			$query = "select *, UNIX_TIMESTAMP(timestamp) as timestamp, ".
					 "(select fullname from users where id=m.user_id) as author, ".
					 "(select subject from forum_topics where id=m.topic_id) as subject ".
					 "from forum_messages m order by timestamp desc limit %d,%d";

			return $this->db->execute($query, $offset, $count);
		}

		public function get_message($message_id) {
			return $this->db->entry("forum_messages", $message_id);
		}

		public function get_topic_id($message_id) {
			if (($message = $this->db->entry("forum_messages", $message_id)) == false) {
				return false;
			}

			return $message["topic_id"];
		}

		public function save_oke($message) {
			$result = true;

			if (trim($message["content"]) == "") {
				$this->output->add_message("Empty message not allowed.");
				$result = false;
			}

			return $result;
		}

		public function update_message($message) {
			$keys = array("content");

			return $this->db->update("forum_messages", $message["id"], $message, $keys) !== false;
		}

		public function delete_message($message_id) {
			if (($message = $this->db->entry("forum_messages", $message_id)) == false) {	
				return false;
			}

			if ($this->db->delete("forum_messages", $message_id) == false) {
				return false;
			}
			
			$query = "select count(*) as messages from forum_messages where topic_id=%d";
			if (($result = $this->db->execute($query, $message["topic_id"])) == false) {
				return false;
			}

			if ((int)$result[0]["messages"] == 0) {
				$this->db->delete("forum_topics", $message["topic_id"]);
			}

			return true;
		}
	}
?>
