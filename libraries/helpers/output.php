<?php
	/* Generate link from text
	 *
	 * INPUT:  string text
	 * OUTPUT: string link
	 * ERROR:  -
	 */
	function make_link($text) {
		$result = "";
		$len = strlen($text);
		for ($i = 0; $i < $len; $i++) {
			if (($text[$i] >= "a") && ($text[$i] <= "z")) {
				$result .= $text[$i];
			} else if (($text[$i] >= "A") && ($text[$i] <= "Z")) {
				$result .= strtolower($text[$i]);
			} else if (($text[$i] >= "0") && ($text[$i] <= "9")) {
				$result .= $text[$i];
			} else if ($text[$i] == " ") {
				$result .= "-";
			}
		}

		return $result;
	}

	/* Truncate text
	 *
	 * INPUT:  string text, int length
	 * OUTPUT: string truncated text
	 * ERROR:  -
	 */
	function truncate_text($text, $length) {
		if (strlen($text) <= $length) {
			return $text;
		}

		$is_space = ($text[$length] === " ");
		$text = substr($text, 0, $length);
		if ($is_space == false) {
			if (($pos = strrpos($text, " ")) !== false) {
				$text = substr($text, 0, $pos);
			}
		}

		return $text."...";
	}

	/* Truncate HTML
	 *
	 * INPUT:  string HTML, int length
	 * OUTPUT: string truncated HTML
	 * ERROR:  -
	 */
	function truncate_html($html, $length) {
		$open_tags = array();
		$html_len = strlen($html);

		for ($i = 0; $i < $html_len; $i++) {
			if ($html[$i] == "<") {
				$name_begin = $i + 1;
				if (($tag_end = strpos($html, ">", $name_begin)) === false) {
					continue;
				}
				$i = $tag_end;

				if ($html[$tag_end - 1] == "/") {
					continue;
				}

				if ($open_tag = ($html[$name_begin] == "/")) {
					array_pop($open_tags);
				} else {
					if (($name_end = strpos($html, " ", $name_begin)) === false) {
						$name_end = $tag_end;
					} else if ($name_end > $tag_end) {
						$name_end = $tag_end;
					}

					$tag = substr($html, $name_begin, $name_end - $name_begin);
					array_push($open_tags, $tag);
				}
			} else if (--$length == 0) {
				break;
			}
		}

		$html = substr($html, 0, $i + 1);
		if ($length == 0) {
			$html .= "...";
		}

		$open_tags = array_reverse($open_tags);
		foreach ($open_tags as $tag) {
			$html .= "</".$tag.">";
		}

		return $html;
	}
?>
