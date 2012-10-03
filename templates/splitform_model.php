<?php
	class XXX_model extends splitform_model {
		protected $forms = array(
			array(
				"template" => "template_name",
				"elements" => array("key1", "key2")));

		public function form_data_oke($data) {
			$result = true;
			foreach ($this->forms[$this->current]["elements"] as $element) {
				if (trim($data[$element]) == "") {
					$this->output->add_message($element." cannot be empty.");
					$result = false;
				}
			}

			return $result;
		}
	}
?>
