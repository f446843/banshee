<?php
	/* libraries/newsletter.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class newsletter extends email {
		protected $content_type = "text/html";
		private $footers = array();

		/* Constructor
		 *
		 * INPUT:  string subject[, string e-mail][, string name]
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($subject, $from_address = null, $from_name = null) {
			array_push($this->footers, "Banshee website: <a href=\"http://".$_SERVER["SERVER_NAME"]."/\">".$_SERVER["SERVER_NAME"]."</a>");
			array_push($this->footers, "To unsubscribe from this newsletter, click <a href=\"http://".$_SERVER["SERVER_NAME"]."/newsletter\">here</a>.");
			parent::__construct($subject, $from_address, $from_name);
		}

		/* Add e-mail footer
		 *
		 * INPUT:  string footer
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function add_footer($str) {
			array_push($this->footers, $str);
		}

		/* Set newsletter content
		 *
		 * INPUT:  string content
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function message($content) {	
			$content = str_replace("\n\n", "</p>\n<p>", $content);
			$content = str_replace("\n", "<br>", $content);

			$footer = implode("<span style=\"margin:0 10px\">|</span>", $this->footers);

			$message = file_get_contents("../extra/newsletter.txt");
			$this->set_message_fields(array(
				"TITLE"   => $this->subject,
				"CONTENT" => $content,
				"FOOTER"  => $footer));

			parent::message($message);
		}
	}
?>
