<?php
	class admin_page_model extends model {
		private $default_layout = "Default layout";

		public function get_pages() {
			$query = "select id, url, private, title, visible from pages order by url";

			return $this->db->execute($query);
		}

		public function get_page($page_id) {
			if (($page = $this->db->entry("pages", $page_id)) == false) {
				return false;
			}

			$query = "select role_id,level from page_access where page_id=%d";
			if (($roles = $this->db->execute($query, $page_id)) === false) {
				return false;
			}

			$page["roles"] = array();
			foreach ($roles as $role) {
				$page["roles"][$role["role_id"]] = $role["level"];
			}

			return $page;
		}

		public function get_url($page_id) {
			if (($page = $this->db->entry("pages", $page_id)) == false) {
				return false;
			}

			return $page["url"];
		}

		public function get_roles() {
			$query = "select id, name from roles order by name";

			return $this->db->execute($query);
		}

		public function get_layouts() {
			if (($fp = fopen("../views/includes/banshee.xslt", "r")) == false) {
				return false;
			}

			$result = array($this->default_layout);
			while (($line = fgets($fp)) !== false) {
				if (strpos($line, "apply-templates") !== false) {
					list(, $layout) = explode('"', $line);
					array_push($result, $layout);
				}
			}

			fclose($fp);

			return $result;
		}

		public function save_oke($page) {
			global $supported_languages;

			$result = true;

			if (valid_input(trim($page["url"]), VALIDATE_URL, VALIDATE_NONEMPTY) == false) {
				$this->output->add_message("URL is empty or contains invalid characters.");
				$result = false;
			} else if ((strpos($page["url"], "//") !== false) || ($page["url"][0] !== "/")) {
				$this->output->add_message("Invalid URL.");
				$result = false;
			}

			if (in_array($page["language"], array_keys($supported_languages)) == false) {
				$this->output->add_message("Language not supported.");
				$result = false;
			}

			if (($layouts = $this->get_layouts()) != false) {
				if (in_array($page["layout"], $layouts) == false) {
					$this->output->add_message("Invalid layout.");
					$result = false;
				}
			}

			if (trim($page["title"]) == "") {
				$this->output->add_message("Empty title not allowed.");
				$result = false;
			}

			if (valid_input($page["language"], VALIDATE_NONCAPITALS, 2) == false) {
				$this->output->add_message("Invalid language code.");
				$result = false;
			}

			$module = ltrim($page["url"], "/");
			$public_pages = page_to_module(public_pages());
			$private_pages = page_to_module(private_pages());
			if (in_array($module, $public_pages) || in_array($module, $private_pages)) {
				$this->output->add_message("URL belongs to a module.");
				$result = false;
			} else {
				$query = "select * from pages where id!=%d and url=%s limit 1";
				if (($page = $this->db->execute($query, $page["id"], $page["url"])) != false) {
					if (count($page) > 0) {
						$this->output->add_message("URL belongs to another page.");
						$result = false;
					}
				}
			}

			return $result;
		}

		public function save_access($page_id, $roles) {
			if ($this->db->query("delete from page_access where page_id=%d", $page_id) === false) {
				return false;
			}

			if (is_array($roles) == false) {
				return true;
			}

			foreach ($roles as $role_id => $has_role) {
				if (is_false($has_role) || ($role_id == ADMIN_ROLE_ID)) {
					continue;
				}

				$values = array(
					"page_id" => (int)$page_id,
					"role_id" => (int)$role_id,
					"level"   => 1);
				if ($this->db->insert("page_access", $values) === false) {
					return false;
				}
			}

			return true;
		}

		public function create_page($page) {
			$keys = array("id", "url", "layout", "language", "private", "style",
			              "title", "description", "keywords", "content",
			              "visible", "back");
			$page["id"] = null;
			$page["private"] = is_true($page["private"]) ? 1 : 0;
			$page["visible"] = is_true($page["visible"]) ? 1 : 0;
			$page["back"] = is_true($page["back"]) ? 1 : 0;

			if ($page["style"] == $this->default_layout) {
				$page["style"] = null;
			}

			if ($this->db->query("begin") == false) {
				return false;
			} else if ($this->db->insert("pages", $page, $keys) === false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->save_access($this->db->last_insert_id, $page["roles"]) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function update_page($page, $page_id) {
			$keys = array("url", "language", "layout", "private", "style",
			              "title", "description", "keywords", "content",
			              "visible", "back");
			$page["private"] = is_true($page["private"]) ? 1 : 0;
			$page["visible"] = is_true($page["visible"]) ? 1 : 0;
			$page["back"] = is_true($page["back"]) ? 1 : 0;

			if ($page["style"] == $this->default_layout) {
				$page["style"] = null;
			}

			if ($this->db->query("begin") == false) {
				return false;
			} else if ($this->db->update("pages", $page_id, $page, $keys) === false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->save_access($page_id, $page["roles"]) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}


		public function delete_page($page_id) {
			$queries = array(
				array("delete from page_access where page_id=%d", $page_id),
				array("delete from pages where id=%d", $page_id));

			return $this->db->transaction($queries);
		}
	}
?>
