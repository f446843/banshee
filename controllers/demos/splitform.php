<?php
	class demos_splitform_controller extends splitform_controller {
		protected $back = "demos";

		protected function process_form_data($data) {
			return true;
		}

		public function execute() {
			$this->model->default_value("content", "Hello world");

			if ($_SERVER["REQUEST_METHOD"] == "GET") {
				$this->model->reset_form_progress();
			}

			parent::execute();
		}
	}
?>
