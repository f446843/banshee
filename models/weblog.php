<?php
	class weblog_model extends model {
		private $show_user = null;

		public function limit_to_user($user) {
			if (is_int($user) == false) {
				if (($user_data = $this->db->entry("user", $user, "username")) == false) {
					return false;
				}

				$user = (int)$user_data["id"];
			}

			$this->show_user = $user;

			return false;
		}

		private function get_weblog_tags($weblog_id) {
			$query = "select t.* from weblog_tags t, weblog_tagged a ".
					 "where t.id=a.weblog_tag_id and a.weblog_id=%d order by tag";

			return $this->db->execute($query, $weblog_id);
		}

		public function get_last_weblogs($count) {
			$query = "select w.*, UNIX_TIMESTAMP(w.timestamp) as timestamp, u.fullname as author, ".
					 "(select count(*) from weblog_comments where weblog_id=w.id) as comment_count ".
					 "from weblogs w, users u ".
					 "where w.user_id=u.id and visible=%d ".
					 "order by w.timestamp desc limit %d";

			if (($weblogs = $this->db->execute($query, YES, $count)) === false) {
				return false;
			}

			/* Tags
			 */
			foreach ($weblogs as $idx => $weblog) {
				if (($weblogs[$idx]["tags"] = $this->get_weblog_tags($weblog["id"])) === false) {
					return false;
				}
			}

			return $weblogs;
		}

		public function get_weblog($weblog_id) {
			/* Weblog
			 */
			$query = "select w.*, UNIX_TIMESTAMP(w.timestamp) as timestamp, u.fullname as author ".
					 "from weblogs w, users u ".
					 "where w.user_id=u.id and w.id=%d and visible=%d";
			if (($result = $this->db->execute($query, $weblog_id, YES)) == false) {
				return false;
			}
			$weblog = $result[0];

			/* Tags
			 */
			if (($weblog["tags"] = $this->get_weblog_tags($weblog_id)) === false) {
				return false;
			}

			/* Comments
			 */
			$query = "select *, UNIX_TIMESTAMP(timestamp) as timestamp ".
					 "from weblog_comments where weblog_id=%d order by timestamp";
			if (($weblog["comments"] = $this->db->execute($query, $weblog_id)) === false) {
				return false;
			}

			return $weblog;
		}

		/* Tag public functions
		 */
		public function get_all_tags() {
			$query = "select distinct t.* from weblog_tags t, weblog_tagged l, weblogs w ".
			         "where t.id=l.weblog_tag_id and l.weblog_id=w.id and w.visible=%d ".
			         "order by tag";

			return $this->db->execute($query, YES);
		}

		public function get_tag($tag_id) {
			if (($tag = $this->db->entry("weblog_tags", $tag_id)) == false) {
				return false;
			}

			return $tag["tag"];
		}

		public function get_tagged_weblogs($tag_id) {
			$query = "select w.id, w.title, UNIX_TIMESTAMP(w.timestamp) as timestamp, u.fullname as author ".
					 "from weblogs w, weblog_tagged t, users u ".
					 "where w.id=t.weblog_id and t.weblog_tag_id=%d and w.user_id=u.id and visible=%d ".
					 "order by timestamp desc";

			return $this->db->execute($query, $tag_id, YES);
		}

		/* Time public functions
		 */
		public function get_years() {
			$query = "select distinct year(timestamp) as year from weblogs ".
			         "where visible=%d order by timestamp desc";

			return $this->db->execute($query, YES);
		}

		public function get_periods() {
			$query = "select distinct month(timestamp) as month, year(timestamp) as year from weblogs ".
			         "where visible=%d order by timestamp desc";

			return $this->db->execute($query, YES);
		}

		public function get_weblogs_of_period($year, $month = null) {
			$query = "select w.id, w.title, UNIX_TIMESTAMP(w.timestamp) as timestamp, u.fullname as author, month(timestamp) as month ".
					 "from weblogs w, users u ".
					 "where w.user_id=u.id and year(w.timestamp)=%d ";
			$args = array($year);

			if ($month != null) {
				$query .= "and month(w.timestamp)=%d ";
				array_push($args, $month);
			}

			$query .= "and visible=%d order by timestamp desc";
			array_push($args, YES);

			return $this->db->execute($query, $args);
		}

		public function comment_oke($comment) {
			$result = true;

			if (trim($comment["author"]) == "") {
				$this->output->add_message("Please, fill in your name.");
				$result = false;
			}

			if (trim($comment["content"]) == "") {
				$this->output->add_message("Please, enter your message.");
				$result = false;
			} else {
				$message = new message($comment["content"]);
				if ($message->is_spam) {
					$this->output->add_message("Message seen as spam.");
					$result = false;
				}
			}

			return $result;
		}

		public function add_comment($comment) {
			$keys = array("id", "weblog_id", "author", "content", "timestamp", "ip_address");

			$comment["id"] = null;
			$comment["timestamp"] = null;
			$comment["ip_address"] = $_SERVER["REMOTE_ADDR"];

			if ($this->db->insert("weblog_comments", $comment, $keys) === false) {
				return false;
			}

			$this->send_notification($comment["weblog_id"], $comment["content"]);

			return true;
		}

		private function send_notification($weblog_id, $comment) {
			if (($weblog = $this->db->entry("weblogs", $weblog_id)) === false) {
				return false;
			} else if (($author = $this->db->entry("users", $weblog["user_id"])) === false) {
				return false;
			}

			$weblog_url = "http://".$_SERVER["SERVER_NAME"]."/".$this->page->module."/".$weblog_id;

			$cms_url = "http://".$_SERVER["SERVER_NAME"]."/admin/weblog/".$weblog_id;
			if (($key = one_time_key($this->db, $author["id"])) !== false) {
				$cms_url .= "?login=".$key;
			}

			$message =
				"<body>".
				"<p>The following comment has been added to your weblog post on the '".$this->settings->head_title."' website.</p>".
				"<p>\"<i>".$comment."</i>\"</p>".
				"<p>Click <a href=\"".$weblog_url."\">here</a> to visit the weblog page or <a href=\"".$cms_url."\">here</a> to visit the weblog CMS page.</p>".
				"</body>";

			$email = new email("Weblog comment posted", $this->settings->webmaster_email);
			$email->message($message);
			$email->send($author["email"], $author["fullname"]);
		}
	}
?>
