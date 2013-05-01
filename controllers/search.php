<?php
	require("../libraries/helpers/output.php");

	class search_controller extends controller {
		private $sections = array(
			"agenda"     => "Agenda",
			"dictionary" => "Dictionary",
			"forum"      => "Forum",
			"mailbox"    => "Mailbox",
			"news"       => "News",
			"pages"      => "Pages",
			"weblog"     => "Weblog");

		/* Log the search query
		 */
		private function log_search_query($query) {
			if (($fp = fopen("../logfiles/search.log", "a")) == false) {
				return;
			}

			fputs($fp, $_SERVER["REMOTE_ADDR"]."|".date("Y-m-d H:i:s")."|".$query."\n");
			fclose($fp);
		}

		/* Search directly in database
		 */
		public function execute() {
			if ($this->user->logged_in == false) {
				unset($this->sections["mail"]);
			}

			if (isset($_SESSION["search"]) == false) {
				$_SESSION["search"] = array();
				foreach ($this->sections as $section => $label) {
					$_SESSION["search"][$section] = true;
				}
			}

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$this->log_search_query($_POST["query"]);
				foreach ($this->sections as $section => $label) {
					$_SESSION["search"][$section] = is_true($_POST[$section]);
				}
			}

			$this->output->add_css("banshee/js_pagination.css");
			$this->output->add_javascript("jquery/jquery.js");
			$this->output->add_javascript("banshee/pagination.js");
			$this->output->add_javascript("search.js");
			$this->output->run_javascript("document.getElementById('query').focus()");

			$this->output->add_tag("query", $_POST["query"]);
			$this->output->open_tag("sections");
			foreach ($this->sections as $section => $label) {
				$params = array(
					"label"   => $label,
					"checked" => show_boolean($_SESSION["search"][$section]));
				$this->output->add_tag("section", $section, $params);
			}
			$this->output->close_tag();

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (strlen(trim($_POST["query"])) < 3) {
					$this->output->add_tag("result", "Search query too short.");
				} else if (($result = $this->model->search($_POST, $this->sections)) === false) {
					/* Error
					 */
					$this->output->add_tag("result", "Search error.");
				} else if (count($result) == 0) {
					$this->output->add_tag("result", "No matches found.");
				} else {
					/* Results
					 */
					foreach ($result as $section => $hits) {
						$this->output->open_tag("section", array(	
							"section" => $section,
							"label"   => $this->sections[$section]));
						foreach ($hits as $hit) {
							$hit["content"] = strip_tags($hit["content"]);
							$hit["content"] = preg_replace('/\[.*?\]/', "", $hit["content"]);
							$hit["content"] = truncate_text($hit["content"], 400);
							$this->output->record($hit, "hit");
						}
						$this->output->close_tag();
					}
				}
			}
		}
	}
?>
