<?php
	/* libraries/secure_cookie.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class secure_cookie {
		private $crypto = null;
		private $validity_check = "banshee";
		private $expire = null;

		/* Constructor
		 *
		 * INPUT:  object database
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($settings) {
			$this->crypto = new AES256($settings->secret_website_code);
			$this->expire = time() + 30 * DAY;
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			if (($value = $_COOKIE[$key]) === null) {
				return null;
			}

			if (($value = base64_decode($value)) === false) {
				return null;
			}

			if (($value = $this->crypto->decrypt($value)) === false) {
				return null;
			}

			if (substr($value, 0, 7) !== $this->validity_check) {
				return null;
			}
			$value = substr($value, 7);

			return json_decode($value, true);
		}

		/* Set setting
		 *
		 * INPUT:  string key, mixed value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __set($key, $value) {
			$value = $this->validity_check.json_encode($value);

			if (($value = $this->crypto->encrypt($value)) === false) {
				return;
			}

			$value = base64_encode($value);

			$_COOKIE[$key] = $value;
			setcookie($key, $value, $this->expire);
		}

		/* Set cookie expire time
		 *
		 * INPUT:  integer timeout
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function set_expire_time($time) {
			$this->expire = time() + $time;
		}
	}
?>
