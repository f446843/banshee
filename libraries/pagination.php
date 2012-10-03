<?php
	/* libraries/pagination.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class pagination {
		private $output = null;
		private $name = null;
		private $page = 0;
		private $max_page = null;
		private $page_size = null;
		private $list_size = null;
		private $error = false;

		/* Constructor
		 *
		 * INPUT:  object output, string name, int page size, int list size
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($output, $name, $page_size, $list_size) {
			$this->output = $output;
			$this->name = $name;
			$this->page_size = $page_size;
			$this->list_size = $list_size;

			if (($this->page_size <= 0) || ($this->list_size <= 0)) {
				$this->error = true;
				return;
			}

			/* Calculate maximum page number
			 */
			$this->max_page = $this->list_size / $this->page_size;
			if ($this->max_page == floor($this->max_page)) {
				$this->max_page -= 1;
			} else {
				$this->max_page = floor($this->max_page);
			}

			/* Initialize session storage
			 */
			if (is_array($_SESSION["pagination"]) == false) {
				$_SESSION["pagination"] = array();
			}
			if (isset($_SESSION["pagination"][$name]) == false) {
				$_SESSION["pagination"][$name] = $this->page;
			}

			/* Calulate page number
			 */
			$this->page = &$_SESSION["pagination"][$name];
			if (isset($_GET["offset"])) {
				if (valid_input($_GET["offset"], VALIDATE_NUMBERS, VALIDATE_NONEMPTY) == false) {
					$this->page = 0;
				} else if (($this->page = (int)$_GET["offset"]) > $this->max_page) {
					$this->page = $this->max_page;
				}
			}

			$this->output->add_css("includes/pagination.css");
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			switch ($key) {
				case "offset": return $this->page * $this->page_size;
				case "size": return $this->page_size;
			}

			return null;
		}

		/* Set active page to 0
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function reset() {
			$this->page = 0;
		}

		/* Generate XML for the browse links
		 *
		 * INPUT:  int number of page links, int step size of arrow links
		 * OUTPUT: boolean xml generated
		 * ERROR:  -
		 */
		public function show_browse_links($max_links = 7, $step = 7) {
			if ($this->error) {
				return false;
			}
			$max_links = (floor($max_links / 2) * 2) + 1;
			
			/* Calculate minimum and maximum page number
			 */
			if ($this->max_page > $max_links) {
				$min = $this->page - floor($max_links / 2);
				$max = $this->page + floor($max_links / 2);

				if ($min < 0) {
					$max -= $min;
					$min = 0;
				} else if ($max > $this->max_page) {
					$min -= ($max - $this->max_page);
					$max = $this->max_page;
				}
			} else {
				$min = 0;
				$max = $this->max_page;
			}

			/* Generate XML for browse links
			 */
			$this->output->open_tag("pagination", array(
				"page" => $this->page, 
				"max"  => $this->max_page,
				"step" => $step));
			for ($page = $min; $page <= $max; $page++) {
				$this->output->add_tag("page", $page);
			}
			$this->output->close_tag();

			return true;
		}

		/* Returns content of table for current page
		 *
		 * INPUT:  object database, string table name[, string column name for ordering]
		 * OUTPUT: array table content
		 * ERROR:  false
		 */
		public function get_items($db, $table, $order = "id") {
			$query = "select * from %S order by %S limit %d,%d";

			return $db->execute($query, $table, $order, $this->offset, $this->size);
		}
	}
?>
