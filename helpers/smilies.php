<?php
	/* libraries/smilies.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	/* Translate text smilies to smiley images
	 *
	 * INPUT:  string message
	 * OUTPUT: string message
	 * ERROR:  -
	 */
	function translate_smilies($str) {
		foreach (config_file("smilies") as $smiley) {
			$smiley = explode("\t", chop($smiley));
			$text = array_shift($smiley);
			$image = "<img src=\"/images/smilies/".array_pop($smiley)."\">";

			$text_len = strlen($text);
			if (substr($str, 0, $text_len + 1) == $text." ") {
				$str = $image.substr($str, $text_len);
			}

			/* At beginning
			 */
			$str = str_replace(" ".$text, " ".$image, $str);
		}

		return $str;
	}
?>
