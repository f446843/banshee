<?php
	class admin_languages_controller extends tablemanager_controller {
		protected $name = "Language";
		protected $pathinfo_offset = 2;
		protected $icon = "languages.png";
		protected $back = "admin";

		public function execute() {
			if (is_a($this->language, "language")) {
				parent::execute();
			} else {
				$this->output->open_tag("tablemanager");
				$this->output->add_tag("name", "Language");
				$this->output->add_tag("result", "Multiple languages are not supported by this website.", array("url" => "admin", "seconds" => "5"));
				$this->output->close_tag();
			}
		}
	}
?>
