<?php
	class admin_poll_model extends model {
		public function get_polls() {
			$query = "select id, question, UNIX_TIMESTAMP(begin) as begin, UNIX_TIMESTAMP(end) as end ".
					 "from polls order by begin desc";

			return $this->db->execute($query);
		}

		public function get_poll($poll_id) {
			if (($poll = $this->db->entry("polls", $poll_id)) == false) {
				return false;
			}

			$begin = strtotime($poll["begin"]);
			$today = strtotime("today 00:00:00");
			if ($begin < $today) {
				return false;
			}

			$query = "select * from poll_answers where poll_id=%d";
			if (($answers = $this->db->execute($query, $poll["id"])) === false) {
				return false;
			}

			$poll["answers"] = array();
			foreach ($answers as $answer) {
				array_push($poll["answers"], $answer["answer"]);
			}

			return $poll;
		}

		public function save_oke($poll) {
			$result = true;

			if (trim($poll["question"]) == "") {
				$this->output->add_message("Fill in the question.");
				$result = false;
			}

			$answers = 0;
			foreach ($poll["answers"] as $answer) {
				if (trim($answer) != "") {
					$answers++;
				}
			}
			if ($answers < 2) {
				$this->output->add_message("Fill in at least 2 answers.");
				$result = false;
			} else if ($answers > $this->settings->poll_max_answers) {
				$this->output->add_message("Too many answers given.");
				$result = false;
			}

			if ((valid_date($poll["begin"]) == false) || (valid_date($poll["end"]) == false)) {
				$this->output->add_message("Invalid begin or end date.");
				$result = false;
			} else {
				$begin = strtotime($poll["begin"]);
				$end = strtotime($poll["end"]);
				$today = strtotime("today 00:00:00");

				if ($begin < $today) {
					$this->output->add_message("Begin date must not be in the past.");
					$result = false;
				}
				if ($begin > $end) {
					$this->output->add_message("End date must not be before begin date.");
					$result = false;
				}
			}

			return $result;
		}

		private function poll_editable($poll_id) {
			if (($poll = $this->db->entry("polls", $poll_id)) == false) {
				return false;
			}
			$begin = strtotime($poll["begin"]);
			$today = strtotime("today 00:00:00");

			return $begin > $today;
		}

		private function create_answers($poll_id, $answers) {
			foreach ($answers as $answer) {
				if (trim($answer) == "") {
					continue;
				}

				$answer = array(
					"id"      => null,
					"poll_id" => (int)$poll_id,
					"answer"  => $answer,
					"votes"   => 0);
				if ($this->db->insert("poll_answers", $answer) === false) {
					return false;
				}
			}

			return true;
		}

		public function create_poll($poll) {
			$keys = array("id", "question", "begin", "end");

			$this->db->query("begin");

			if ($this->db->insert("polls", $poll, $keys) === false) {
				$this->db->query("rollback");
				return false;
			}

			if ($this->create_answers($this->db->last_insert_id, $poll["answers"]) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function update_poll($poll) {
			$keys = array("question", "begin", "end");

			if ($this->poll_editable($poll["id"]) == false) {
				return false;
			}

			$this->db->query("begin");

			if ($this->db->update("polls", $poll["id"], $poll, $keys) === false) {
				$this->db->query("rollback");
				return false;
			}

			$query = "delete from poll_answers where poll_id=%d";
			if ($this->db->query($query, $poll["id"]) == false) {
				$this->db->query("rollback");
				return false;
			}

			if ($this->create_answers($poll["id"], $poll["answers"]) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function delete_poll($poll_id) {
			if ($this->poll_editable($poll_id) == false) {
				return false;
			}

			$queries = array(
				array("delete from poll_answers where poll_id=%d", $poll_id),
				array("delete from polls where id=%d", $poll_id));

			return $this->db->transaction($queries);
		}
	}
?>
