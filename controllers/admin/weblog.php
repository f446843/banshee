<?php
	require_once("../libraries/helpers/output.php");

	class admin_weblog_controller extends controller {
		private function show_weblog_overview() {
			$user_id = $this->user->is_admin ? null : $this->user->id;

			if (($weblog_count = $this->model->count_weblogs($user_id)) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$paging = new pagination($this->output, "admin_forum", $this->settings->admin_page_size, $weblog_count);

			if (($weblogs = $this->model->get_weblogs($paging->offset, $paging->size, $user_id)) === false) {
				$this->output->add_tag("result", "Database error.");
				return;
			}

			$this->output->open_tag("overview");

			$this->output->open_tag("weblogs");
			foreach ($weblogs as $weblog) {
				$weblog["timestamp"] = date("j F Y, H:i", $weblog["timestamp"]);
				$this->output->record($weblog, "weblog");
			}
			$this->output->close_tag();

			$paging->show_browse_links();

			$this->output->close_tag();
		}

		private function show_weblog_form($weblog) {
			$this->output->add_javascript("ckeditor/ckeditor.js");
			$this->output->add_javascript("banshee/start_ckeditor.js");

			$this->output->open_tag("edit");

			$weblog["visible"] = show_boolean($weblog["visible"]);
			$this->output->record($weblog, "weblog");

			/* Tags
			 */
			$tagged = array();
			if (isset($weblog["tag"])) {
				$tagged = $weblog["tag"];
			} else if (($weblog_tags = $this->model->get_weblog_tags($weblog["id"])) != false) {
				foreach ($weblog_tags as $tag) {
					array_push($tagged, $tag["id"]);
				}
			}

			$this->output->open_tag("tags");
			if (($tags = $this->model->get_tags()) != false) {
				foreach ($tags as $tag) {
					$this->output->add_tag("tag", $tag["tag"], array(
						"id" => $tag["id"],
						"selected" => show_boolean(in_array($tag["id"], $tagged))));
				}
			}
			$this->output->close_tag();

			/* Comments
			 */
			$this->output->open_tag("comments");
			if (($weblog_comments = $this->model->get_weblog_comments($weblog["id"])) != false) {
				foreach ($weblog_comments as $comment) {
					$comment["content"] = truncate_text($comment["content"], 100);
					$this->output->record($comment, "comment");
				}
			}
			$this->output->close_tag();

			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				/* Remove weblog RSS from cache
				 */
				$this->output->remove_from_cache("weblog_rss");

				/* Verify access rights
				 */
				$access_allowed = true;
				if (isset($_POST["id"])) {
					if (($weblog = $this->model->get_weblog($_POST["id"])) == false) {
						$access_allowed = false;
					} else if (($this->user->is_admin == false) && ($weblog["user_id"] != $this->user->id)) {
						$access_allowed = false;
					}
				}

				if ($access_allowed == false) {
					$this->output->add_tag("result", "You are not allowed to edit or delete this weblog.");
				} else if ($_POST["submit_button"] == "Save weblog") {
					/* Save weblog
					 */
					if ($this->model->save_oke($_POST) == false) {
						$this->show_weblog_form($_POST);
					} else if (isset($_POST["id"]) == false) {
						/* Create weblog
						 */
						if ($this->model->create_weblog($_POST) == false) {
							$this->output->add_message("Database error while creating weblog.");
							$this->show_weblog_form($_POST);
						} else {
							$this->user->log_action("weblog %d created", $this->db->last_insert_id);
							$this->show_weblog_overview();
						}
					} else {
						/* Update weblog
						 */
						if ($this->model->update_weblog($_POST) == false) {
							$this->output->add_message("Database error while updating weblog.");
							$this->show_weblog_form($_POST);
						} else {
							$this->user->log_action("weblog %d updated", $_POST["id"]);
							$this->show_weblog_overview();
						}
					}
				} else if ($_POST["submit_button"] == "Delete weblog") {
					/* Delete weblog
					 */
					if ($this->model->delete_weblog($_POST["id"]) == false) {
						$this->output->add_tag("result", "Error while deleting weblog.");
					} else {
						$this->user->log_action("weblog %d deleted", $_POST["id"]);
						$this->show_weblog_overview();
					}
				} else {
					$this->show_weblog_overview();
				}
			} else if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Show weblog
				 */
				if (($weblog = $this->model->get_weblog($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "Weblog not found.");
				} else if (($this->user->is_admin == false) && ($weblog["user_id"] != $this->user->id)) {
					$this->output->add_tag("result", "You are not allowed to edit this weblog.");
				} else {
					$this->show_weblog_form($weblog);
				}
			} else if ($this->page->pathinfo[2] == "new") {
				/* New weblog
				 */
				$weblog = array(
					"visible" => 1);
				$this->show_weblog_form($weblog);
			} else {
				/* Show weblog overview
				 */
				$this->show_weblog_overview();
			}
		}
	}
?>
