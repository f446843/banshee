<?php
	class demos_layout_controller extends controller {
		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$this->output->set_layout($_POST["layout"]);
				$current = $_POST["layout"];
			} else {
				$current = LAYOUT_SITE;
			}

			if (($layouts = $this->model->get_layouts()) == false) {
				$this->output->add_tag("result", "No layouts found.");
				return;
			}

			$this->output->open_tag("layouts");
			foreach ($layouts as $layout) {
				$this->output->add_tag("layout", $layout, array("current" => show_boolean($layout == $current)));
			}
			$this->output->close_tag();
		}
	}
?>
