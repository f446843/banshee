<?php
	class admin_weblog_model extends model {
		public function count_weblogs($user_id = null) {
			$query = "select count(*) as count from weblogs";
			if ($user_id !== null) {
				$query .= " where user_id=%d";
			}

			if (($result = $this->db->execute($query, $user_id)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_weblogs($offset, $count, $user_id = null) {
			$query = "select w.id, w.user_id, w.title, UNIX_TIMESTAMP(w.timestamp) as timestamp, u.fullname as author ".
					 "from weblogs w, users u where w.user_id=u.id";
			$args = array();

			if ($user_id !== null) {
				$query .= " and w.user_id=%d";
				array_push($args, $user_id);
			}

			$query .= " order by timestamp desc limit %d,%d";
			array_push($args, $offset, $count);

			return $this->db->execute($query, $args);
		}

		public function get_weblog($weblog_id) {
			return $weblog = $this->db->entry("weblogs", $weblog_id);
		}

		public function get_weblog_tags($weblog_id) {
			$query = "select t.* from weblog_tags t, weblog_tagged a ".
					 "where t.id=a.weblog_tag_id and a.weblog_id=%d";

			return $this->db->execute($query, $weblog_id);
		}

		public function get_weblog_comments($weblog_id) {
			$query = "select * from weblog_comments where weblog_id=%d order by timestamp";

			return $this->db->execute($query, $weblog_id);
		}

		public function get_tags() {
			$query = "select * from weblog_tags order by tag";

			return $this->db->execute($query);
		}

		public function save_oke($weblog) {
			$result = true;

			if (trim($weblog["title"]) == "") {
				$this->output->add_message("Title can't be empty.");
				$result = false;
			}

			if (trim($weblog["content"]) == "") {
				$this->output->add_message("Weblog content can't be empty.");
				$result = false;
			}

			return $result;
		}

		private function handle_tags($weblog_id, $weblog) {
			/* Update tags
			 */
			if (is_array($weblog["tag"]) == false) {
				$weblog["tag"] = array();
			}

			/* Create new tags
			 */
			$tags = explode(",", $weblog["new_tags"]);
			foreach ($tags as $tag) {
				if (($tag = trim($tag)) == "") {
					continue;
				}

				if (($entry = $this->db->entry("weblog_tags", $tag, "tag")) != false) {
					array_push($weblog["tag"], $entry["id"]);
				} else if ($this->db->insert("weblog_tags", array("id" => null, "tag" => $tag)) !== false) {
					array_push($weblog["tag"], $this->db->last_insert_id);
				}
			}

			$this->db->query("delete from weblog_tagged where weblog_id=%d", $weblog_id);
			foreach (array_unique($weblog["tag"]) as $tag) {
				$tags = array(
					"weblog_id"     => (int)$weblog_id,
					"weblog_tag_id" => (int)$tag);
				if ($this->db->insert("weblog_tagged", $tags) === false) {
					return false;
				}
			}

			return $this->delete_unused_tags();
		}

		private function delete_unused_tags() {
			/* Delete unused tags
			 */
			$query = "select id, (select count(*) from weblog_tagged where weblog_tag_id=t.id) as count ".
					 "from weblog_tags t";
			if (($counts = $this->db->execute($query)) === false) {
				return false;
			}

			foreach ($counts as $count) {
				if ($count["count"] == 0) {
					if ($this->db->delete("weblog_tags", $count["id"]) === false) {
						return false;
					}
				}
			}

			return true;
		}

		public function create_weblog($weblog) {
			$keys = array("id", "user_id", "title", "content", "timestamp", "visible");

			$weblog["id"] = null;
			$weblog["user_id"] = $this->user->id;
			$weblog["timestamp"] = null;
			$weblog["visible"] = is_true($weblog["visible"]) ? YES : NO;

			if ($this->db->query("begin") === false) {
				return false;
			} else if ($this->db->insert("weblogs", $weblog, $keys) === false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->handle_tags($this->db->last_insert_id, $weblog) == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}

		public function update_weblog($weblog) {
			$keys = array("title", "content", "visible");

			$weblog["visible"] = is_true($weblog["visible"]) ? YES : NO;

			if ($this->db->query("begin") === false) {
				return false;
			} else if ($this->db->update("weblogs", $weblog["id"], $weblog, $keys) === false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->handle_tags($weblog["id"], $weblog) == false) {
				$this->db->query("rollback");
				return false;
			}

			/* Delete comments
			 */
			if (is_array($weblog["comment"])) {
				foreach ($weblog["comment"] as $comment_id) {
					if ($this->db->delete("weblog_comments", $comment_id) === false) {
						$this->db->query("rollback");
						return false;
					}
				}
			}

			return $this->db->query("commit") != false;
		}

		public function delete_weblog($weblog_id) {
			if ($this->db->query("begin") == false) {
				return false;
			} else if ($this->db->query("delete from weblog_comments where weblog_id=%d", $weblog_id) == false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->db->query("delete from weblog_tagged where weblog_id=%d", $weblog_id) == false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->db->query("delete from weblogs where id=%d", $weblog_id) == false) {
				$this->db->query("rollback");
				return false;
			} else if ($this->delete_unused_tags() == false) {
				$this->db->query("rollback");
				return false;
			}

			return $this->db->query("commit") != false;
		}
	}
?>
