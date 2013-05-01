<?php
	/* libraries/aes256.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class AES256 {
		private $crypto_key = null;
		private $resource = null;
		private $iv = null;

		/* Constructor
		 *
		 * INPUT:  string crypto key[, string iv]
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($crypto_key, $iv = null) {
			$this->crypto_key = $crypto_key;

			if (($this->resource = mcrypt_module_open(MCRYPT_RIJNDAEL_256, "", "cbc", "")) == false) {
				return;
			}

			$iv_size = mcrypt_enc_get_iv_size($this->resource);
			if ($iv === null) {
				$iv = hash("sha256", $this->crypto_key);
			} else while (strlen($iv) < $iv_size) {
				$iv .= $iv;
			}
			$this->iv = substr($iv, 0, $iv_size);
		}

		/* Destructor
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __destruct() {
			if ($this->resource !== null) {
				mcrypt_module_close($this->resource);
				$this->resource = null;
			}
		}

		/* Encrypt/decrypt data
		 *
		 * INPUT:  string data, boolean encrypt
		 * OUTPUT: string data
		 * ERROR:  false
		 */
		private function crypto($data, $encrypt) {
			if ($this->resource === null) {
				return false;
			}

			$result = mcrypt_generic_init($this->resource, $this->crypto_key, $this->iv);
			if (($result === false) || ($result < 0)) {
				return false;
			}

			if ($encrypt) {
				$result = mcrypt_generic($this->resource, $data);
			} else {
				$result = rtrim(mdecrypt_generic($this->resource, $data), chr(0));
			}

			mcrypt_generic_deinit($this->resource);

			return $result;
		}

		/* Encrypt data
		 *
		 * INPUT:  string plain text data
		 * OUTPUT: string encrypted data
		 * ERROR:  false
		 */
		public function encrypt($data) {
			return $this->crypto($data, true);
		}

		/* Decrypt data
		 *
		 * INPUT:  string encrypted data
		 * OUTPUT: string plain text data
		 * ERROR:  false
		 */
		public function decrypt($data) {
			return $this->crypto($data, false);
		}
	}
?>
