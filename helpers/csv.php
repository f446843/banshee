<?php
	/* Prepare data for CSV output
	 *
	 * INPUT:  string/array data
	 * OUTPUT: string data
	 * ERROR:  -
	 */
	function csv_output($data) {
		if (is_array($data) == false) {
			$data = str_replace('"', '""', $data);
			$data = '"'.$data.'"';
		} else {
			foreach ($data as &$item) {
				$item = csv_output($item);
			}
			$data = implode(",", $data);
		}

		return $data;
	}

	function csv_to_array($csv, $sep = ",") {
		$in_quoted = false;

		$len = strlen($csv);
		$result = array();
		$index = 0;

		for ($i = 0; $i < $len; $i++) {
			if ($csv[$i] === '"') {
				if ($csv[$i + 1] === '"') {
					$result[$index] .= '"';
					$i++;
				} else {
					$in_quoted = ($in_quoted == false);
				}
			} else if ($in_quoted) {
				$result[$index] .= $csv[$i];
			} else if ($csv[$i] == $sep) {
				$index++;
			} else {
				$result[$index] .= $csv[$i];
			}
		}

		if ($in_quoted) {
			return false;
		}

		return $result;
	}
?>
