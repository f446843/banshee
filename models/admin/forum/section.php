<?php
	class admin_forum_section_model extends tablemanager_model {
		protected $table = "forums";
		protected $order = "title";
		protected $elements = array(
			"title" => array(
				"label"    => "Title",
				"type"     => "varchar",
				"overview" => true,
				"required" => true),
			"description" => array(
				"label"    => "Description",
				"type"     => "text",
				"overview" => true,
				"required" => true),
			"order" => array(
				"label"    => "Order",
				"type"     => "integer",
				"overview" => true));

		public function delete_oke($section_id) {
			$query = "select count(*) as count from forum_topics where forum_id=%d";
			if (($section = $this->db->execute($query, $section_id)) === false) {
				return false;
			}

			if ($section[0]["count"] > 0) {
				$this->output->add_message("This forum section contains topics.");
				return false;
			}

			return true;
		}
	}
?>
