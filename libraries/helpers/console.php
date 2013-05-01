<?php
	function get_terminal_width() {
		$output = explode(";", exec("stty -a | grep columns"));

		foreach ($output as $line) {
			list($key, $value) = explode(" ", ltrim($line), 2);
			if ($key == "columns") {
				return (int)$value;
			}
		}

		return 80;
	}
?>
