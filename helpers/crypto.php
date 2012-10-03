<?php
	/* libraries/crypto.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	define("MD5_BLOCKSIZE", 64);

	/* Calculate HMACr-MD5
	 *
	 * INPUT:  string message, string key
	 * OUTPUT: string hash
	 * ERROR:  -
	 */
	function hmac_md5($message, $key) {
		$opad = str_repeat("\x5c", MD5_BLOCKSIZE);
		$ipad = str_repeat("\x36", MD5_BLOCKSIZE);

		if (strlen($key) > MD5_BLOCKSIZE) {
			$key = md5($key);
		}

		if (strlen($key) < MD5_BLOCKSIZE) {
			$key = str_pad($key, MD5_BLOCKSIZE, "\x00");
		}

		for ($i = 0; $i < strlen($key) - 1; $i++) {
			$ipad[$i] = $ipad[i] xor $key[$i];
			$opad[$i] = $opad[i] xor $key[$i];
		}

		return md5($opad.md5($ipad.$message));
	}

	/* Encrypt message via AES256
	 *
	 * INPUT:  string message, string key
	 * OUTPUT: string encrypted message
	 * ERROR:  -
	 */
	function encrypt_AES256($message, $key) {
		$iv = "";

		if ($key == "") {
			return false;
		} else do {
			$iv .= $key;
		} while (strlen($iv) < 32);

		$key = substr($key, 0, 32);
		$iv = substr($iv, 0, 32);

		return bin2hex(mcrypt_cbc(MCRYPT_RIJNDAEL_256, $key, $message, MCRYPT_ENCRYPT, $iv));
	}

	/* Decrypt message via AES256
	 *
	 * INPUT:  string encrypted message, string key
	 * OUTPUT: string message
	 * ERROR:  false
	 */
	function decrypt_AES256($message, $key) {
		$iv = "";

		if ($key == "") {
			return false;
		} else do {
			$iv .= $key;
		} while (strlen($iv) < 32);

		$key = substr($key, 0, 32);
		$message = pack("H".strlen($message), $message);
		$iv = substr($iv, 0, 32);

		return rtrim(mcrypt_cbc(MCRYPT_RIJNDAEL_256, $key, $message, MCRYPT_DECRYPT, $iv), chr(0));
	}

	/* Calculate SHA256 hash
	 *
	 * INPUT:  string message
	 * OUTPUT: string hash
	 * ERROR:  -
	 */
	function sha256($str) {
		return hash("sha256", $str);
	}

	/* Calculate SHA512 hash
	 *
	 * INPUT:  string message
	 * OUTPUT: string hash
	 * ERROR:  -
	 */
	function sha512($str) {
		return hash("sha512", $str);
	}
?>
