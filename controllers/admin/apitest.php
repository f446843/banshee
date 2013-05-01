<?php
	class admin_apitest_controller extends controller {
		private function show_form($data) {
			$this->output->open_tag("form");

			$methods = array("GET", "POST", "PUT", "DELETE");
			$this->output->open_tag("methods");
			foreach ($methods as $method) {
				$this->output->add_tag("method", $method);
			}
			$this->output->close_tag();

			$types = array("ajax", "xml", "json");
			$this->output->open_tag("types");
			foreach ($types as $type) {
				$this->output->add_tag("type", $type);
			}
			$this->output->close_tag();

			$this->output->record($data);

			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (($result = $this->model->request_result($_POST)) === false) {
					$this->output->add_message("Request error.");
				} else {
					if ($result["status"] != 200) {
						$this->output->add_message("Request result: %s", $result["status"]);
					}
					$this->output->add_tag("result", $result["body"]);
				}

				$this->show_form($_POST);
			} else {
				$data = array("url" => "/");
				$this->show_form($data);
			}
		}
	}
?>
