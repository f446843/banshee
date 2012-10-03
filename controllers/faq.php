<?php
	class faq_controller extends controller {
		public function execute() {
			$this->output->title = "F.A.Q.";

			if (($sections = $this->model->get_all_sections()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}
			
			if (($faqs = $this->model->get_all_faqs()) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$this->output->add_javascript("jquery/jquery.js");

			$this->output->open_tag("overview");
			
			$this->output->open_tag("sections");
			foreach ($sections as $section) {
				$this->output->add_tag("section", $section["label"], array("id" => $section["id"]));
			}
			$this->output->close_tag();

			$this->output->open_tag("faqs");
			$section_id = 0;
			$number = 1;
			foreach ($faqs as $faq) {
				if ($section_id != $faq["section_id"]) {
					$section_id = $faq["section_id"];
					#$number = 1;
				}

				$faq["question"] = $number.". ".$faq["question"];
				$this->output->record($faq, "faq");

				$number++;
			}
			$this->output->close_tag();

			$this->output->close_tag();
		}
	}
?>
