<?php
	class admin_dictionary_model extends model {
		public function count_words() {
			$query = "select count(*) as count from dictionary";

			if (($result = $this->db->execute($query)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_words($offset, $count) {
			$query = "select * from dictionary order by word limit %d,%d";

			return $this->db->execute($query, $offset, $count);
		}

		public function get_word($word_id) {
			return $this->db->entry("dictionary", $word_id);
		}

		public function save_oke($word) {
			$result = true;

			if (valid_input($word["word"], VALIDATE_LETTERS.VALIDATE_NUMBERS." -_", VALIDATE_NONEMPTY) == false) {
				$this->output->add_message("Word contains invalid characters or is empty.");
				$result = false;
			} else if (valid_input($word["word"], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				$this->output->add_message("Word must contain letters.");
				$result = false;
			}
			if (trim($word["short_description"]) == "") {
				$this->output->add_message("The short description cannot be empty.");
				$result = false;
			}

			return $result;
		}

		public function create_word($word) {
			$keys = array("id", "word", "short_description", "long_description");

			$word["id"] = null;

			return $this->db->insert("dictionary", $word, $keys) !== false;
		}

		public function update_word($word) {
			$keys = array("word", "short_description", "long_description");

			return $this->db->update("dictionary", $word["id"], $word, $keys) !== false;
		}

		public function delete_word($word_id) {
			return $this->db->delete("dictionary", $word_id);
		}
	}
?>
