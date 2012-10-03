<?php
	class admin_albums_controller extends tablemanager_controller {
		protected $name = "Photo album";
		protected $pathinfo_offset = 2;
		protected $back = "admin";
		protected $icon = "albums.png";
		protected $page_size = 25;
		protected $pagination_links = 7;
		protected $pagination_step = 1;
		protected $foreign_null = "---";
	}
?>
