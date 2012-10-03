<?php
	class admin_organisation_controller extends tablemanager_controller {
		protected $name = "Organisation";
		protected $back = "admin";
		protected $pathinfo_offset = 2;
		protected $icon = "organisations.png";

		public function show_item_form($item) {
			if (valid_input($item["id"], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				if (($users = $this->model->get_users($item["id"])) !== false) {
					$this->output->open_tag("users");
					foreach ($users as $user) {
						$this->output->record($user, "user");
					}
					$this->output->close_tag();
				}
			}

			parent::show_item_form($item);
		}
	}
?>
