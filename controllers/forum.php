<?php
	class forum_controller extends controller {
		private $url = null;

		private function show_forum_overview() {
			if (($forums = $this->model->get_forums()) === false) {
				$this->output->add_tag("result", "Database error.", $this->url);
			} else {
				$this->output->open_tag("forums");
				foreach ($forums as $forum) {
					$this->output->record($forum, "forum");
				}
				$this->output->close_tag();
			}
		}

		private function show_topic_form($topic) {
			$this->output->add_javascript("jquery/jquery.js");
			$this->output->add_javascript("forum.js");

			$this->output->record($topic, "newtopic");
			$this->show_smilies();
		}

		private function show_forum($forum_id) {
			if (($count = $this->model->count_topics($forum_id)) === false) {
				$this->output->add_tag("result", "Database error while counting topics.");
				return;
			}

			$paging = new pagination($this->output, "forum_".$forum_id, $this->settings->forum_page_size, $count);

			if (($forum = $this->model->get_forum($forum_id, $paging->offset, $paging->size)) === false) {
				$this->output->add_tag("result", "Forum not found.", $this->url);
				return;
			}

			$this->output->title = $forum["title"]." - Forum";

			$this->output->open_tag("forum", array("id" => $forum["id"]));
			$this->output->add_tag("title", $forum["title"]);

			$this->output->open_tag("topics");
			foreach ($forum["topics"] as $topic) {
				if ($this->user->logged_in) {
					$topic["unread"] = show_boolean($this->model->last_topic_view($topic["id"]) < $topic["timestamp"]);
				}
				$topic["starter"] = isset($topic["visitor"]) ? $topic["visitor"] : $topic["user"];
				$topic["timestamp"] = date("j F Y, H:i", $topic["timestamp"]);
				$this->output->record($topic, "topic");
			}
			$this->output->close_tag();

			$paging->show_browse_links();

			$this->output->close_tag();
		}

		private function show_smilies() {
			$smilies = config_file("smilies");

			$this->output->open_tag("smilies");
			foreach ($smilies as $smiley) {
				$smiley = explode("\t", chop($smiley));
				$text = array_shift($smiley);
				$image = array_pop($smiley);

				$this->output->add_tag("smiley", $image, array("text" => $text));
			}
			$this->output->close_tag();
		}

		private function show_topic($topic_id, $response = null) {
			$moderate = $this->user->access_allowed("admin/forum");

			if (($topic = $this->model->get_topic($topic_id)) == false) {
				$this->output->add_tag("result", "Topic not found.", $this->url);
			} else {
				$this->output->add_javascript("jquery/jquery.js");
				$this->output->add_javascript("forum.js");

				$this->output->title = $topic["subject"]." - Forum";
				$this->output->open_tag("topic", array("id" => $topic["id"], "forum_id" => $topic["forum_id"]));
				$this->output->add_tag("subject", $topic["subject"]);

				if ($this->user->logged_in) {
					$last_view = $this->model->last_topic_view($topic["id"], true);
				}
				foreach ($topic["messages"] as $message) {
					if ($this->user->logged_in) {
						$message["unread"] = show_boolean($last_view < $message["timestamp"]);
					}
					if ($message["user_id"] == "") {
						$message["author"] = $message["username"];
						$message["usertype"] = "unregistered";
					} else {
						$message["usertype"] = "registered";
					}
					$message["timestamp"] = date("j F Y, H:i", $message["timestamp"]);
					$message["content"] = preg_replace("/\[(config|code|quote)\]([\r\n]*)/", "[$1]", $message["content"]);

					$post = new message($message["content"]);
					$post->unescaped_output();
					$post->translate_bbcodes();
					$post->translate_smilies();
					$message["content"] = $post->content;
					unset($post);

					$this->output->record($message, "message", array("moderate" => show_boolean($moderate)));
				}

				if ($response != null) {
					$this->output->record($response, "response");
				}

				$this->output->close_tag();

				$this->show_smilies();
			}
		}

		public function execute() {
			$this->output->description = "Banshee forum";
			$this->output->keywords = "forum";
			$this->output->title = "Forum";

			$this->url = array("url" => $this->page->page);

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Create topic") {
					/* Create new topic
					 */
					if ($this->model->topic_oke($_POST) == false) {
						$this->show_topic_form($_POST);
					} else if ($this->model->create_topic($_POST) == false) {
						$this->output->add_message("Database error while creating topic.");
						$this->show_topic_form($_POST);
					} else {
						$this->output->add_tag("result", "Topic has been created.", array("url" => $this->page->page."/".$_POST["forum_id"]));
					}
				} else if ($_POST["submit_button"] == "Post response") {
					/* Respond to topic
					 */
					if ($this->model->response_oke($_POST) == false) {
						$this->show_topic($_POST["topic_id"], $_POST);
					} else if ($this->model->create_response($_POST) == false) {
						$this->output->add_message("Database error while saving response.");
						$this->show_topic_form($_POST);
					} else {
						$this->output->add_tag("result", "Response has been saved.", array("url" => $this->page->page."/topic/".$_POST["topic_id"]));
					}
				} else {
					$this->show_forum_overview();
				}
			} else if ($this->page->pathinfo[1] == "topic") {
				/* Show topic
				 */
				$this->show_topic($this->page->pathinfo[2]);
			} else if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				if ($this->page->pathinfo[2] == "new") {
					/* Start new topic
					 */
					$topic = array("forum_id" => $this->page->pathinfo[1]);
					$this->show_topic_form($topic);
				} else {
					/* Show forum
					 */
					$this->show_forum($this->page->pathinfo[1]);
				}
			} else {
				/* Show forums
				 */
				$this->show_forum_overview();
			}
		}
	}
?>
