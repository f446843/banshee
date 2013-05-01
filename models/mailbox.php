<?php
	require("../libraries/helpers/output.php");

	class mailbox_model extends model {
		public function get_inbox() {
			$query = "select m.id, m.from_user_id, m.subject, UNIX_TIMESTAMP(m.timestamp) as timestamp, m.read, u.fullname as user ".
			         "from mailbox m, users u where m.from_user_id=u.id and m.to_user_id=%d and (m.deleted_by is null or m.deleted_by!=m.to_user_id) ".
			         "order by timestamp desc";

			return $this->db->execute($query, $this->user->id);
		}

		public function get_sentbox() {
			$query = "select m.id, m.from_user_id, m.subject, UNIX_TIMESTAMP(m.timestamp) as timestamp, m.read, u.fullname as user ".
			         "from mailbox m, users u where m.to_user_id=u.id and m.from_user_id=%d and (m.deleted_by is null or m.deleted_by!=m.from_user_id) ".
			         "order by timestamp desc";

			return $this->db->execute($query, $this->user->id);
		}

		public function get_mail($mail_id) {
			$query = "select m.*, u.fullname as from_user from mailbox m, users u ".
			         "where m.from_user_id=u.id and m.id=%d and (m.to_user_id=%d or m.from_user_id=%d)";

			if (($result = $this->db->execute($query, $mail_id, $this->user->id, $this->user->id)) == false) {
				return false;
			}
			$mail = $result[0];

			if ($mail["to_user_id"] == $this->user->id) {
				$this->db->update("mailbox", $mail_id, array("read" => YES));
			}

			return $mail;
		}

		public function get_recipients() {
			$query = "select id, fullname from users where id!=%d and status!=%s";

			return $this->db->execute($query, $this->user->id, USER_STATUS_DISABLED);
		}

		public function get_reply_mail($mail_id) {
			if (($mail = $this->get_mail($mail_id)) == false) {
				return false;
			}

			$mail["subject"] = "Re: ".$mail["subject"];
			$mail["message"] = wordwrap($mail["message"], 50, "\n");
			$mail["message"] = "\n\n\n> ".str_replace("\n", "\n> ", $mail["message"]);

			return $mail;
		}

		public function send_oke($mail) {
			$result = true;

			if ($this->db->entry("users", $mail["to_user_id"]) == false) {
				$this->output->add_message("Unknown recipient.");
				$result = false;
			}

			if (trim($mail["subject"]) == "") {
				$this->output->add_message("Empty subject not allowed.");
				$result = false;
			}

			if (trim($mail["message"]) == "") {
				$this->output->add_message("Empty message not allowed.");
				$result = false;
			}

			return $result;
		}

		public function send_mail($mail) {
			$data = array(
				"id"           => null,
				"from_user_id" => (int)$this->user->id,
				"to_user_id"   => (int)$mail["to_user_id"],
				"subject"      => $mail["subject"],
				"message"      => $mail["message"],
				"timestamp"    => null,
				"read"         => NO,
				"deleted_by"   => null);

			return $this->db->insert("mailbox", $data) != false;
		}

		public function delete_mail($mail_id) {
			if (($mail = $this->get_mail($mail_id)) == false) {
				return false;
			}

			if ($mail["deleted_by"] == null) {
				return $this->db->update("mailbox", $mail_id, array("deleted_by" => $this->user->id)) != false;
			}

			return $this->db->delete("mailbox", $mail_id) != false;
		}
	}
?>
