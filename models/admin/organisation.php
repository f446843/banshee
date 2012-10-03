<?php
	class admin_organisation_model extends tablemanager_model {
		protected $table = "organisations";
		protected $elements = array(
			"name" => array(
				"label"    => "Name",
				"type"     => "varchar",
				"overview" => true,
				"required" => true,
				"unique"   => true));

		public function get_users($organisation_id) {
			$query = "select * from users where organisation_id=%d order by fullname";

			return $this->db->execute($query, $organisation_id);
		}

		public function delete_oke($item_id) {
			if (parent::delete_oke($item_id) == false) {
				return false;
			}

			$query = "select count(*) as count from users where organisation_id=%d";
			
			if (($result = $this->db->execute($query, $item_id)) === false) {	
				$this->output->add_message("Database error.");
				return false;
			}

			if ((int)$result[0]["count"] > 0) {
				$this->output->add_message("Organisation in use.");
				return false;
			}

			return true;
		}
	}
?>
