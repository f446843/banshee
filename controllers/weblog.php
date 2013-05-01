<?php
	class weblog_controller extends controller {
		private $url = null;

		private function show_last_weblogs() {
			if (($weblogs = $this->model->get_last_weblogs($this->settings->weblog_page_size)) === false) {
				$this->output->add_tag("result", "Database error.", $this->url);
				return;
			}

			$this->output->open_tag("weblogs");

			foreach ($weblogs as $weblog) {
				$this->output->open_tag("weblog", array("id" => $weblog["id"]));

				$weblog["timestamp"] = date("j F Y, H:i", $weblog["timestamp"]);
				$this->output->record($weblog);

				/* Tags
				 */
				$this->output->open_tag("tags");
				foreach ($weblog["tags"] as $tag) {
					$this->output->add_tag("tag", $tag["tag"], array("id" => $tag["id"]));
				}
				$this->output->close_tag();

				$this->output->close_tag();
			}

			$this->output->close_tag();
		}

		private function show_weblog($weblog_id) {
			if (($weblog = $this->model->get_weblog($weblog_id)) === false) {
				$this->output->add_tag("result", "Weblog not found.", $this->url);
				return;
			}

			$this->output->title = $weblog["title"]." - Weblog";

			$weblog["timestamp"] = date("j F Y, H:i", $weblog["timestamp"]);

			$this->output->open_tag("weblog", array("id" => $weblog["id"]));

			$this->output->add_tag("title", $weblog["title"]);
			$this->output->add_tag("content", $weblog["content"]);
			$this->output->add_tag("author", $weblog["author"]);
			$this->output->add_tag("timestamp", $weblog["timestamp"]);

			/* Tags
			 */
			$this->output->open_tag("tags");
			foreach ($weblog["tags"] as $tag) {
				$this->output->add_tag("tag", $tag["tag"], array("id" => $tag["id"]));
				$this->output->keywords .= ", ".$tag["tag"];
			}
			$this->output->close_tag();

			/* Comments
			 */
			$this->output->open_tag("comments");
			foreach ($weblog["comments"] as $comment) {
				unset($comment["weblog_id"]);
				unset($comment["ip_address"]);
				$message = new message($comment["content"]);
				$message->unescaped_output();
				$message->translate_smilies();
				$comment["content"] = $message->content;
				unset($message);

				$comment["timestamp"] = date("j F Y, H:i", $comment["timestamp"]);
				$this->output->record($comment, "comment");
			}
			$this->output->close_tag();

			$this->output->close_tag();
		}

		private function show_comment($comment) {
			$this->output->open_tag("comment");
			$this->output->add_tag("author", $comment["author"]);
			$this->output->add_tag("content", $comment["content"]);
			$this->output->close_tag();
		}

		public function execute() {
			global $months_of_year;

			$this->output->title = "Weblog";
			$this->output->description = "Weblog";
			$this->output->keywords = "weblog";
			$this->output->add_alternate("Weblog", "application/rss+xml", "/weblog.xml");

			$this->url = array("url" => $this->page->page);

			/* Sidebar
			 */
			$this->output->open_tag("sidebar");

			/* Tags
			 */
			if (($tags = $this->model->get_all_tags()) != false) {
				$this->output->open_tag("tags");
				foreach ($tags as $tag) {
					$this->output->add_tag("tag", $tag["tag"], array("id" => $tag["id"]));
				}
				$this->output->close_tag();
			}

			/* Years
			 */
			if (($years = $this->model->get_years()) != false) {
				$this->output->open_tag("years");
				foreach ($years as $year) {
					$this->output->add_tag("year", $year["year"]);
				}
				$this->output->close_tag();
			}

			/* Periods
			 */
			if (($periods = $this->model->get_periods()) != false) {
				$this->output->open_tag("periods");
				foreach ($periods as $period) {
					$link = array("link" => $period["year"]."/".$period["month"]);
					$text = $months_of_year[$period["month"] - 1]." ".$period["year"];
					$this->output->add_tag("period", $text, $link);
				}
				$this->output->close_tag();
			}

			$this->output->close_tag();

			if ($this->page->type == "xml") {
				/* RSS feed
				 */
				$rss = new RSS($this->output);
				if ($rss->fetch_from_cache("weblog_rss") == false) {
					$rss->title = $this->settings->head_title." weblog";
					$rss->description = $this->settings->head_description;

					if (($weblogs = $this->model->get_last_weblogs($this->settings->weblog_rss_page_size)) != false) {
						foreach ($weblogs as $weblog) {
							$link = "/weblog/".$weblog["id"];
							$rss->add_item($weblog["title"], $weblog["content"], $link, $weblog["timestamp"]);
						}
					}
					$rss->to_output();
				}
			} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
				/* Comment submits
				 */
				if ($this->model->comment_oke($_POST) == false) {
					$this->show_comment($_POST);
					$this->show_weblog($_POST["weblog_id"]);
				} else if ($this->model->add_comment($_POST) == false) {
					$this->output->add_message("Error while adding comment.");
					$this->show_comment($_POST);
					$this->show_weblog($_POST["weblog_id"]);
				} else {
					$this->output->add_tag("result", "Comment has been added.", array("url" => $this->page->page."/".$_POST["weblog_id"]));
				}
			} else if (($this->page->pathinfo[1] == "tag") && (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY))) {
				/* Tagged weblogs
				 */
				if (($tag = $this->model->get_tag($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "Unknown tag", $this->url);
				} else if (($weblogs = $this->model->get_tagged_weblogs($this->page->pathinfo[2])) === false) {
					$this->output->add_tag("result", "Error fetching tags", $this->url);
				} else {
					$this->output->title = "Tag ".$tag." - Weblog";

					$this->output->open_tag("list", array("label" => "Weblogs with '".$tag."' tag"));
					foreach ($weblogs as $weblog) {
						$this->output->record($weblog, "weblog");
					}
					$this->output->close_tag();
				}
			} else if (($this->page->pathinfo[1] == "period") && valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY) && valid_input($this->page->pathinfo[3], VALIDATE_NUMBERS)) {
				/* Weblogs of certain period
				 */
				if (($weblogs = $this->model->get_weblogs_of_period($this->page->pathinfo[2], $this->page->pathinfo[3])) === false) {
					$this->output->add_tag("result", "Error fetching weblogs", $this->url);
				} else {
					if ($this->page->pathinfo[3] == null) {
						$this->output->title = "Year ".$this->page->pathinfo[2]." - Weblog";
					} else {
						$month = $months_of_year[$this->page->pathinfo[3] - 1];
						$this->output->title = $month." ".$this->page->pathinfo[2]." - Weblog";
					}

					$month = 0;
					$count = count($weblogs);
					for ($i = 0; $i < $count; $i++) {
						if ((int)$weblogs[$i]["month"] != $month) {
							if ($month != 0) {
								$this->output->close_tag();
							}
							if ($i < $count) {
								$this->output->open_tag("list", array("label" => $months_of_year[$weblogs[$i]["month"] - 1]." ".$this->page->pathinfo[2]));
							}
						}
						$this->output->record($weblogs[$i], "weblog");
						$month = (int)$weblogs[$i]["month"];
					}
					if ($month != 0) {
						$this->output->close_tag();
					}
				}
			} else if (valid_input($this->page->pathinfo[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Show weblog
				 */
				$this->show_weblog($this->page->pathinfo[1]);
			} else {
				/* Show last weblogs
				 */
				$this->show_last_weblogs();
			}
		}
	}
?>
