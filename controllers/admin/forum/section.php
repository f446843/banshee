<?php
	class admin_forum_section_controller extends tablemanager_controller {
		protected $name = "Forum section";
		protected $pathinfo_offset = 3;
		protected $back = "admin/forum";
		protected $icon = null;
		protected $page_size = 25;
		protected $pagination_links = 7;
		protected $pagination_step = 1;
		protected $foreign_null = "---";
		protected $browsing = "pagination";
	}
?>
