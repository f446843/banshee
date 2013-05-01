<?php
	class admin_faq_model extends model {
		public function get_all_sections() {
			$query = "select * from faq_sections order by label";

			return $this->db->execute($query);
		}

		public function get_all_faqs() {
			$query = "select f.* from faqs f, faq_sections s ".
					 "where f.section_id=s.id ".
					 "order by s.label, f.question";

			return $this->db->execute($query);
		}

		public function get_faq($faq_id) {
			return $this->db->entry("faqs", $faq_id);
		}

		public function save_oke($faq) {
			$result = true;

			if (trim($faq["question"]) == "") {
				$this->output->add_message("Fill in the question.");
				$result = false;
			}

			if (trim($faq["answer"]) == "") {
				$this->output->add_message("Fill in the answer.");
				$result = false;
			}

			if ($faq["select"] == "new") {
				if (trim($faq["label"]) == "") {
					$this->output->add_message("Fill in the section.");
					$result = false;
				}
			} else if ($faq["select"] != "old") {
				$this->output->add_message("Unknown section type.");
				$result = false;
			}

			return $result;
		}

		public function get_section_id($label) {
			if (($section = $this->db->entry("faq_sections", $label, "label")) != false) {
				return $section["id"];
			}

			$values = array("id" => null, "label" => $label);
			if ($this->db->insert("faq_sections", $values) !== false) {
				return $this->db->last_insert_id;
			}

			return false;
		}

		private function delete_unused_sections() {
			$query = "select id, (select count(*) from faqs where section_id=s.id) as count ".
					 "from faq_sections s";
			if (($sections = $this->db->execute($query)) === false) {
				return false;
			}

			foreach ($sections as $section) {
				if ($section["count"] == 0) {
					if ($this->db->delete("faq_sections", $section["id"]) == false) {
						return false;
					}
				}
			}

			return true;
		}

		public function create_faq($faq) {
			$keys = array("id", "question", "answer", "section_id");

			$faq["id"] = null;

			if ($this->db->query("begin") == false) {
				return false;
			}

			if ($faq["select"] == "new") {
				if (($faq["section_id"] = $this->get_section_id($faq["label"])) == false) {
					$this->db->query("rollback");
					return false;
				}
			} else if ($faq["select"] == "old") {
				$faq["section_id"] = (int)$faq["section_id"];
			} else {
				return false;
			}

			if ($this->db->insert("faqs", $faq, $keys) === false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function update_faq($faq) {
			$keys = array("question", "answer", "section_id");

			if ($this->db->query("begin") == false) {
				return false;
			}

			if ($faq["select"] == "new") {
				if (($faq["section_id"] = $this->get_section_id($faq["label"])) == false) {
					$this->db->query("rollback");
					return false;
				}
			} else if ($faq["select"] == "old") {
				$faq["section_id"] = (int)$faq["section_id"];
			} else {
				return false;
			}

			if ($this->db->update("faqs", $faq["id"], $faq, $keys) === false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->delete_unused_sections() == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function delete_faq($faq_id) {
			if ($this->db->query("begin") == false) {
				return false;
			} else if ($this->db->delete("faqs", $faq_id) == false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->delete_unused_sections() == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}
	}
?>
