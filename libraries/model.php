<?php
	/* libraries/oo/model.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	abstract class model {
		protected $db = null;
		protected $settings = null;
		protected $user = null;
		protected $page = null;
		protected $output = null;
		protected $language = null;

		/* Constructor
		 *
		 * INPUT:  object database, object settings, object user, object page, object output[, object language]
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($database, $settings, $user, $page, $output, $language = null) {
			$this->db = $database;
			$this->settings = $settings;
			$this->user = $user;
			$this->page = $page;
			$this->output = $output;
			$this->language = $language;
		}
	}
?>
