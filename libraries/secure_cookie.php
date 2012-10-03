<?php
	/* libraries/alphabetize.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	require_once("../helpers/crypto.php");

	class secure_cookie {
		private $validity_check = "banshee";
		private $crypto_key = null;
		private $expire = null;

		/* Constructor
		 *
		 * INPUT:  object database
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($settings) {
			$this->crypto_key = $settings->secret_website_code;
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

			$value = decrypt_AES256($value, $this->crypto_key);

			if (substr($value, 0, 7) !== $this->validity_check) {
				return null;
			}

			return json_decode(substr($value, 7), true);
		}

		/* Set setting
		 *
		 * INPUT:  string key, mixed value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __set($key, $value) {
			$value = $this->validity_check.json_encode($value);
			$value = encrypt_AES256($value, $this->crypto_key);
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
