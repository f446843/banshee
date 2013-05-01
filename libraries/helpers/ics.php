<?php
	/* Prepare string for iCalendar output
	 *
	 * INPUT:  string data
	 * OUTPUT: string data
	 * ERROR:  -
	 */
	function ics_output($str) {
		$str = str_replace("\\", "\\\\", $str);
		$str = str_replace("\"", "\\\"", $str);
		$str = str_replace("\r", "", $str);
		$str = str_replace("\n", "\\n", $str);

		return $str;
	}
?>
