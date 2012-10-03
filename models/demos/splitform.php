<?php
	class demos_splitform_model extends splitform_model {
		protected $forms = array(
			array(
				"template" => "form_1",
				"elements" => array("name", "number")),
			array(
				"template" => "form_2",
				"elements" => array("title", "content")),
			array(
				"template" => "form_3",
				"elements" => array("remark")));

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
