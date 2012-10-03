<?php
	class admin_logging_controller extends controller {
		private $height = 100;
		private $page_width = 839;
		private $list_limit = 15;

		private function show_graph($items, $title) {
			static $id = -1;

			$id = $id + 1;
			$max = $this->model->max_value($items, "count");

			$this->output->open_tag("graph", array("title" => $title, "id" => $id, "max" => $max));
			foreach ($items as $item) {
				if ($max > 0) {
					$item["height"] = round($this->height * ($item["count"] / $max));
				} else {
					$item["height"] = 0;
				}
				$item["day"] = date("j F Y", strtotime($item["date"]));

				$this->output->record($item, "item");
			}
			$this->output->close_tag();
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$this->model->delete_referers($_POST);
			}

			$this->output->add_tag("width", floor($this->page_width / LOG_DAYS) - 1);
			$this->output->add_tag("height", $this->height);

			$this->output->add_javascript("jquery/jquery.js");
			$this->output->add_javascript("admin/logging.js");

			$day = valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS."-", VALIDATE_NONEMPTY) ? $this->page->pathinfo[2] : null;

			/* Visits
			 */
			if (($visits = $this->model->get_visits(LOG_DAYS)) === false) {
				return false;
			}
			$this->show_graph($visits, "Visits");

			/* Page views
			 */
			if (($pageviews = $this->model->get_page_views(LOG_DAYS)) === false) {
				return false;
			}
			$this->show_graph($pageviews, "Page views");

			/* Day deselect
			 */
			if ($day !== null) {
				$this->output->add_tag("deselect", date("j F Y", strtotime($day)), array("date" => $day));
			}

			/* Top pages
			 */
			if (($pages = $this->model->get_top_pages($this->list_limit, $day)) === false) {
				return false;
			}

			$this->output->open_tag("pages");
			foreach ($pages as $page) {
				$this->output->record($page, "page");
			}
			$this->output->close_tag();

			/* Search queries
			 */
			if (($queries = $this->model->get_search_queries($this->list_limit, $day)) === false) {
				return false;
			}

			$this->output->open_tag("search");
			foreach ($queries as $query) {
				$this->output->record($query, "query");
			}
			$this->output->close_tag();

			/* Referers
			 */
			$date = date("Y-m-d", strtotime("-7 days"));
			if (($referers = $this->model->get_referers($day)) === false) {
				return false;
			}

			$this->output->open_tag("referers");
			$hostname = null;
			foreach ($referers as $hostname => $host) {
				$total = 0;
				foreach ($host as $referer) {
					$total += $referer["count"];
				}
				$params = array(
					"hostname" => $hostname,
					"count"    => count($host),
					"total"    => $total);
				$this->output->open_tag("host", $params);
				foreach ($host as $referer) {
					$this->output->record($referer, "referer");
				}
				$this->output->close_tag();
			}
			$this->output->close_tag();
		}
	}
?>
