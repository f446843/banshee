<?php
	/* libraries/anti_spam.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	/* Helper function for message_is_spam()
	 */
	function __antispam_log($reason) {
		if (($fp = fopen("../logfiles/spam.log", "a")) != false) {
			fputs($fp, $_SERVER["REMOTE_ADDR"]."|".date("D d M Y H:i:s")."|".$reason."\n");
			fclose($fp);
		}
	}

	/* Determine whether a message is spam or not
	 *
	 * INPUT:  string message
	 * OUTPUT: boolean message is spam
	 * ERROR:  -
	 */
	function message_is_spam($str) {
		$antispam = array();
		$index = false;

		/* Read the configuration file
		 */
		foreach (config_file("antispam") as $line) {
			if ($line[0] == "%") {
				$index = substr($line, 1);
				$antispam[$index] = array();
			} else if ($index === false) {
				list($key, $value) = explode("=", $line, 2);
				$antispam[trim($key)] = trim($value);
			} else {
				array_push($antispam[$index], $line);
			}
		}

		/* Check for blocked IP address
		 */
		foreach ($antispam["blocked_ip"] as $blocked_ip) {
			if ($_SERVER["REMOTE_ADDR"] == $blocked_ip) {
				__antispam_log("blocked ip");
				return true;
			}
		}

		/* Check if POST is done too quickly
		 */
		if (isset($antispam["min_delay"])) {
			if (isset($_SESSION["last_visit"]) == false) {
				__antispam_log("post without requesting form");
				return true;
			} else {
				if (time() - $_SESSION["last_visit"] < $antispam["min_delay"]) {
					__antispam_log("post too quickly");
					return true;
				}
			}
		}

		/* Check for forbidden user agents
		 */
		foreach ($antispam["forbidden_user_agents"] as $agent) {
			if ($_SERVER["HTTP_USER_AGENT"] == $agent) {
				__antispam_log("forbidden user agent (".$agent.")");
				return true;
			}
		}

		/* Check for forbidden words
		 */
		foreach ($antispam["forbidden_words"] as $word) {
			if (stristr($str, $word) != false) {
				__antispam_log("forbidden word (".$word.")");
				return true;
			}
		}

		/* Check for maximum allowed number of links
		 */
		if (isset($antispam["max_links"])) {
			$link_count = max(substr_count($str, "[url"), substr_count($str, "http://"));
			if ($link_count > $antispam["max_links"]) {
				__antispam_log("+".$antispam["max_links"]." links");
				return true;
			}
		}

		/* Check for unreadable characters
		 */
		$letters = 0;
		$numbers = 0;
		$symbols = 0;
		$other   = 0;
		for ($i = 0; $i < strlen($str); $i++) {
			$char = $str[$i];
			if (($char >= "0") && ($char <= "9")) {
				$numbers++;
			} else if (($char >= "A") && ($char <= "Z")) {
				$letters++;
			} else if (($char >= "a") && ($char <= "z")) {
				$letters++;
			} else if (strchr(" !@#$^&*()_+-={}[]<>\|/;:,.'\"", $char) != false)  {
				$symbols++;
			} else {
				$other++;
			}
		}
		if ($other > ($letters + $numbers + $symbols)) {
			__antispam_log("unreadable message");
			return true;
		}

		return false;
	}
?>
