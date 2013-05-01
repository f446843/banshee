<?php
	class admin_settings_controller extends tablemanager_controller {
		protected $name = "Setting";
		protected $pathinfo_offset = 2;
		protected $back = "admin";
		protected $icon = null;
		protected $page_size = 25;
		protected $pagination_links = 7;
		protected $pagination_step = 1;
		protected $foreign_null = "---";

		protected function handle_submit() {
			parent::handle_submit();

			$cache = new cache($this->db, "settings");
			$cache->store("last_updated", time(), 365 * DAY);
		}
	}
?>
