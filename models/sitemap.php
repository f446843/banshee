<?php
	class sitemap_model extends model {
		public function get_public_urls() {
			/* Modules on disk
			 */
			$exclude = array("captcha.png", "login", "logout", "offline", "password", "sitemap.xml");

			$urls = array_diff(public_pages(), $exclude);

			/* Pages from database
			 */
			$query = "select url from pages where private=%d";
			if (($pages = $this->db->execute($query, NO)) != false) {
				foreach ($pages as $page) {
					array_push($urls, ltrim($page["url"], "/"));
				}
			}

			sort($urls);

			return $urls;
		}
	}
?>
