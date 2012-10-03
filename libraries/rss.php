<?php
	/* libraries/rss.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class RSS {
		private $output = null;
		private $protocol = null;
		private $cache_id = null;
		private $title = null;
		private $description = null;
		private $url = null;
		private $items = array();
		private $content_type = "application/rss+xml; charset=utf-8";

		/* Constructor
		 *
		 * INPUT:  object output
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($output) {
			$this->output = $output;

			if (isset($_SERVER["HTTP_SCHEME"])) {
				$this->protocol = $_SERVER["HTTP_SCHEME"];
			} else if ($_SERVER["HTTPS"] == "on") {
				$this->protocol = "https";
			} else {
				$this->protocol = "http";
			}

			$this->url = sprintf("%s://%s/", $this->protocol, $_SERVER["SERVER_NAME"]);
		}

		/* Magic method set
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __set($key, $value) {
			switch ($key) {
				case "title": $this->title = $value; break;
				case "description": $this->description = $value; break;
				case "url": $this->url = $value; break;
			}
		}

		/* Fetch RSS data from cache
		 *
		 * INPUT:  string cache id
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function fetch_from_cache($cache_id) {
			$this->output->content_type = $this->content_type;
			$this->cache_id = $cache_id;

			return $this->output->fetch_from_cache($cache_id);
		}

		/* Add RSS item
		 *
		 * INPUT:  string title, string description, string link, int timestamp
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function add_item($title, $description, $link, $timestamp) {
			if ($link[0] == "/") {
				$link = sprintf("%s://%s%s", $this->protocol, $_SERVER["SERVER_NAME"], $link);
			}

			array_push($this->items, array(
				"title"       => $title,
				"description" => $description,
				"link"        => $link,
				"timestamp"   => date("r", $timestamp)));
		}

		/* Send RSS feed to client
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function to_output() {
			$this->output->content_type = $this->content_type;

			if ($this->cache_id !== null) {
				$this->output->start_caching($this->cache_id);
			}

			$this->output->open_tag("rss_feed");

			if ($this->title !== null) {
				$this->output->add_tag("title", $this->title);
			}
			if ($this->description !== null) {
				$this->output->add_tag("description", $this->description);
			}
			$this->output->add_tag("url", $url);

			$this->output->open_tag("items");
			foreach ($this->items as $item) {
				$this->output->record($item, "item");
			}
			$this->output->close_tag();

			$this->output->close_tag();

			if ($this->cache_id !== null) {
				$this->output->stop_caching();
			}
		}
	}
?>
