<?php
	class forum_model extends model {
		public function get_forums() {
			$query = "select *,(select count(*) from forum_topics where forum_id=f.id) as topics ".
					 "from forums f order by %S";

			return $this->db->execute($query, "order");
		}

		public function get_forum($forum_id, $offset, $limit) {
			$query = "select * from forums where id=%d";

			if (($result = $this->db->execute($query, $forum_id)) == false) {
				return false;
			}
			$forum = $result[0];
			$forum["topics"] = $this->get_topics($forum_id, $offset, $limit);

			return $forum;
		}

		public function count_topics($forum_id) {
			$query = "select count(*) as count from forum_topics where forum_id=%d";

			if (($result = $this->db->execute($query, $forum_id)) == false) {
				return false;
			}

			return $result[0]["count"];
		}

		public function get_topics($forum_id, $offset, $limit) {
			$query = "select *, (select UNIX_TIMESTAMP(timestamp) from forum_messages ".
			         "where topic_id=t.id order by timestamp desc limit 1) as timestamp, ".
			         "(select fullname from forum_messages m, users u where m.user_id=u.id ".
			         "and topic_id=t.id order by timestamp limit 1) as user, ".
			         "(select username from forum_messages m where topic_id=t.id ".
			         "order by timestamp limit 1) as visitor, ".
			         "(select count(*) from forum_messages where topic_id=t.id) as messages ".
			         "from forum_topics t where forum_id=%d order by timestamp desc limit %d,%d";

			return $this->db->execute($query, $forum_id, $offset, $limit);
		}

		public function get_topic($topic_id) {
			if (($topic = $this->db->entry("forum_topics", $topic_id)) == false) {
				return false;
			}

			if (($forum = $this->db->entry("forums", $topic["forum_id"])) == false) {
				return false;
			}
			$topic["title"] = $forum["title"];

			$query = "select *, UNIX_TIMESTAMP(timestamp) as timestamp, ".
			         "(select fullname from users where id=m.user_id) as author ".
			         "from forum_messages m where topic_id=%d order by timestamp";
			if (($topic["messages"] = $this->db->execute($query, $topic_id)) === false) {
				return false;
			}

			return $topic;
		}

		public function last_topic_view($topic_id, $update = false) {
			if (isset($_SESSION["FORUM_LAST_VIEW"]) == false) {
				if (($result = $this->db->entry("forum_last_view", $this->user->id, "user_id")) == false) {
					$_SESSION["FORUM_LAST_VIEW"] = 0;
				} else {
					$_SESSION["FORUM_LAST_VIEW"] = strtotime($result["last_view"]);
				}
				$_SESSION["TOPIC_LAST_VIEW"] = array();

				$result = $_SESSION["FORUM_LAST_VIEW"];
			} else {
				if (isset($_SESSION["TOPIC_LAST_VIEW"][$topic_id]) == false) {
					$_SESSION["TOPIC_LAST_VIEW"][$topic_id] = $_SESSION["FORUM_LAST_VIEW"];
				}

				$result = $_SESSION["TOPIC_LAST_VIEW"][$topic_id];

				if ($update) {
					$_SESSION["TOPIC_LAST_VIEW"][$topic_id] = time();
				}
			}

			$this->db->query("delete from forum_last_view where user_id=%d", $this->user->id);
			$this->db->insert("forum_last_view", array("user_id" => $this->user->id, "last_view" => null));

			return $result;
		}

		public function topic_oke($topic) {
			$result = $this->response_oke($topic);

			if (trim($topic["subject"]) == "") {
				$this->output->add_message("Empty subject not allowed.");
				$result = false;
			}

			return $result;
		}

		public function response_oke($topic) {
			$result = true;

			if ($this->user->logged_in == false) {
				if (trim($topic["username"]) == "") {
					$this->output->add_message("Fill in your name.");
					$result = false;
				} else {
					$name = preg_replace('/  */', " ", trim($topic["username"]));
					$query = "select * from users where fullname=%s";
					if (($x = $this->db->execute($query, $name)) != false) {
						$this->output->add_message("That name is not allowed.");
						$result = false;
					}
				}
			}

			if (trim($topic["content"]) == "") {
				$this->output->add_message("Empty message not allowed.");
				$result = false;
			} else {
				$message = new message($topic["content"]);
				if ($message->is_spam) {
					$this->output->add_message("Message seen as spam.");
					$result = false;
				}
			}

			return $result;
		}

		public function create_topic($topic) {
			$queries = array();
			array_push($queries, array("insert into forum_topics values(null, %d, %s)", $topic["forum_id"], $topic["subject"]));

			if ($this->user->logged_in) {
				$query = "insert into forum_messages values(null, {LAST_INSERT_ID}, %d, null, now(), %s, %s)";
				array_push($queries, array($query, $this->user->id, $topic["content"], $_SERVER["REMOTE_ADDR"]));
			} else {
				$query = "insert into forum_messages values(null, {LAST_INSERT_ID}, null, %s, now(), %s, %s)";
				array_push($queries, array($query, $topic["username"], $topic["content"], $_SERVER["REMOTE_ADDR"]));
			}

			if ($this->db->transaction($queries) === false) {
				return false;
			}

			$this->send_notifications($topic["content"], $this->db->last_insert_id(2));

			return true;
		}

		public function create_response($response) {
			$keys = array("id", "topic_id", "user_id", "username", "timestamp", "content", "ip_address");

			$response["id"] = null;
			if ($this->user->logged_in) {
				$response["user_id"] = $this->user->id;
				$response["username"] = null;
			} else {
				$response["user_id"] = null;
			}
			$response["timestamp"] = null;
			$response["ip_address"] = $_SERVER["REMOTE_ADDR"];

			if ($this->db->insert("forum_messages", $response, $keys) === false) {
				return false;
			}

			$this->send_notifications($response["content"], $response["topic_id"], $db->last_insert_id);

			return true;
		}

		private function send_notifications($message, $topic_id, $message_id = null) {
			if ($this->user->logged_in) {
				return;
			} else if ($this->settings->forum_maintainers == null) {
				return;
			}

			$maintainers = users_with_role($this->db, $this->settings->forum_maintainers);

			$topic_url = "http://".$_SERVER["SERVER_NAME"]."/".$this->page->module."/topic/".$topic_id;
			if ($message_id !== null) {
				$topic_url .= "#".$message_id;
			}

			$email = new email("Forum message posted", $this->settings->webmaster_email);

			foreach ($maintainers as $maintainer) {
				$cms_url = "http://".$_SERVER["SERVER_NAME"]."/admin/forum";
				if (($key = one_time_key($this->db, $maintainer["id"])) !== false) {
					$cms_url .= "?login=".$key;
				}

				$message =
					"<body>".
					"<p>The following message has been added to the forum on the '".$this->settings->head_title."' website.</p>".
					"<p>\"<i>".$message."</i>\"</p>".
					"<p>Click <a href=\"".$topic_url."\">here</a> to visit the forum topic page or <a href=\"".$cms_url."\">here</a> to visit the forum CMS page.</p>".
					"</body>";

				$email->message($message);
				$email->send($maintainer["email"], $maintainer["fullname"]);
			}
		}
	}
?>
