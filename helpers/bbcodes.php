<?php
	/* libraries/bbcodes.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	/* Helper function for translate_bbcodes()
	 */
	function __secure_bbcode_attribute($attr) {
		$data = strtolower(ltrim($attr));
		if (substr($data, 0, 10) == "javascript") {
			$attr = "#";
		} else if (substr($data, 0, 4) == "data") {
			$attr = "#";
		}

		return str_replace("\"", "%22", $attr);
	}

	/* Translate BB-codes to HTML tags
	 *
	 * INPUT:  string BB-code message
	 * OUTPUT: string HTML message
	 * ERROR:  -
	 */
	function translate_bbcodes($str) {
		foreach (config_file("bbcodes") as $line) {
			$line = str_replace("'", "\"", chop($line));
			list($bbcode, $begin, $end) = explode("|", $line, 3);
			$bbcode_len = strlen($bbcode) + 2;
			do {
				$changed = false;
				$link = false;
				if (($open = strpos($str, "[".$bbcode."]")) === false) {
					$open = strpos($str, "[".$bbcode."=");
				}
				$open_end = strpos($str, "]", $open);

				if (($open !== false) && ($open_end !== false)) {
					if ($open + $bbcode_len < $open_end) {
						$param = substr($str, $open + $bbcode_len, $open_end - $open - $bbcode_len);
						$param = __secure_bbcode_attribute($param);
						$new_begin = str_replace("%param%", $param, $begin);
					} else {
						$new_begin = $begin;
						$param = null;
					}

					if ($end == "") {
						$str = substr($str, 0, $open).$new_begin.substr($str, $open_end + 1);
						$changed = true;
					} else if (($close = strpos($str, "[/".$bbcode."]", $open_end)) !== false) {
						$str = substr($str, 0, $close).$end.substr($str, $close + $bbcode_len + 1);

						$text = substr($str, $open_end + 1, $close - $open_end - 1);
						$text = __secure_bbcode_attribute($text);
						if (($bbcode == "url") && ($text != "") && ($param != null)) {
							if (valid_input($text, VALIDATE_LETTERS.VALIDATE_NUMBERS." :\"'()&-+") == false) {
								$str = substr($str, 0, $open_end + 1).$param.substr($str, $close);
							}
						}

						$new_begin = str_replace("%param%", $text, $new_begin);
						$str = substr($str, 0, $open).$new_begin.substr($str, $open_end + 1);

						$changed = true;
					}
				}
			} while ($changed);
		}

		return $str;
	}
?>
