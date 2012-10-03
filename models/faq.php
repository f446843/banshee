<?php
	class faq_model extends model {
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
	}
?>
