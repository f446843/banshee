<?php
	class admin_controller extends controller {
		private $menu = array(
			"Authentication & authorization" => array(
				"Users"         => array("admin/user", "users.png"),
				"Roles"         => array("admin/role", "roles.png"),
				"Organisations" => array("admin/organisation", "organisations.png"),
				"Access"        => array("admin/access", "access.png"),
				"User switch"   => array("admin/switch", "switch.png")),
			"Content" => array(
				"Agenda"        => array("admin/agenda", "agenda.png"),
				"Dictionary"    => array("admin/dictionary", "dictionary.png"),
				"F.A.Q."        => array("admin/faq", "faq.png"),
				"Files"         => array("admin/file", "files.png"),
				"Forum"         => array("admin/forum", "forum.png"),
				"Guestbook"     => array("admin/guestbook", "guestbook.png"),
				"Languages"     => array("admin/languages", "languages.png"),
				"Links"         => array("admin/links", "links.png"),
				"Menu"          => array("admin/menu", "menu.png"),
				"News"          => array("admin/news", "news.png"),
				"Pages"         => array("admin/page", "page.png"),
				"Polls"         => array("admin/poll", "poll.png"),
				"Weblog"        => array("admin/weblog", "weblog.png")),
			"Photo album" => array(
				"Albums"        => array("admin/albums", "albums.png"),
				"Collections"   => array("admin/collection", "collection.png"),
				"Photos"        => array("admin/photos", "photos.png")),
			"Newsletter" => array(
				"Newsletter"    => array("admin/newsletter", "newsletter.png"),
				"Subscriptions" => array("admin/subscriptions", "subscriptions.png")),
			"System" => array(
				"Logging"       => array("admin/logging", "logging.png"),
				"Action log"    => array("admin/action", "action.png"),
				"Settings"      => array("admin/settings", "settings.png")));

		public function execute() {
			if (($this->user->id == 1) && ($this->user->password == "c10b391ff5e75af6ee8469539e6a5428f09eff7e693d6a8c4de0e5525cd9b287")) {
				$this->output->add_system_warning("Don't forget to change the password of the admin account!");
			}

			if ($this->settings->secret_website_code == "CHANGE_ME_INTO_A_RANDOM_STRING") {
				$this->output->add_system_warning("Don't forget to change the secret_website_code setting.");
			}

			if (is_false(MULTILINGUAL)) {
				unset($this->menu["Content"]["Languages"]);
			}

			$access_list = page_access_list($this->db, $this->user);
			$private_pages = private_pages();

			$this->output->open_tag("menu");

			foreach ($this->menu as $text => $section) {

				$this->output->open_tag("section", array(
					"text"  => $text,
					"class" => strtr(strtolower($text), " &", "__")));

				foreach ($section as $text => $info) {
					list($page, $icon) = $info;

					if (in_array($page, $private_pages) == false) {
						continue;
					}

					if (isset($access_list[$page])) {
						$access = show_boolean($access_list[$page] > 0);
					} else {
						$access = show_boolean(true);
					}

					$this->output->add_tag("entry", $page, array(
						"text"   => $text,
						"access" => $access,
						"icon"   => $icon));
				}

				$this->output->close_tag();
			}

			$this->output->close_tag();
		}
	}
?>
