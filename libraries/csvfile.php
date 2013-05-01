<?php
	/* libraries/csvfile.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class csvfile {
		private $data = array();
		private $seperator = ",";

		/* Constructor
		 *
		 * INPUT:  string host[, int port]
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($file = null) {
			if ($file !== null) {
				$this->read($file);
			}
		}

		/* Magic method set
		 *
		 * INPUT:  string key, string value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __set($key, $value) {
			switch ($key) {
				case "seperator": $this->seperator = $value; break;
			}
		}

		/* Flatten array to new array with depth 1
		 *
		 * INPUT:  array data
		 * OUTPUT: array data
		 * ERROR:  -
		 */
		private function array_flatten($data) {
			$result = array();
			foreach ($data as $item) {
				if (is_array($item)) {
					$result = array_merge($result, array_flatten($item));
				} else {
					array_push($result, $item);
				}
			}

			return $result;
		}

		/* Convert CSV line to array
		 *
		 * INPUT:  string csv line
		 * OUTPUT: array data
		 * ERROR:  false
		 */
		private function csv_to_array($csv) {
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
				} else if ($csv[$i] == $this->seperator) {
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

		/* Read CSV file
		 *
		 * INPUT:  string CSV file
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function read($file) {
			if (($fp = fopen($file, "r")) === false) {
				return false;
			}

			$this->data = array();
			while (($line = fgets($fp)) !== false) {
				if (($line = $this->csv_to_array($line)) === false) {
					fclose($fp);
					return false;
				}
				array_push($this->data, $line);
			}

			fclose($fp);

			return true;
		}

		/* Add line to CSV data
		 *
		 * INPUT:  array csv line
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function add_line() {
			$line = func_get_args();
			$line = $this->array_flatten($line);

			foreach ($line as $i => $item) {
				$item = str_replace("\r", "" , $item);
				$item = str_replace("\n", " " , $item);
				$line[$i] = $item;
			}

			array_push($this->data, $line);

			return true;
		}

		/* Add raw CSV to CSV data
		 *
		 * INPUT:  string csv data
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function add_raw($data) {
			$data = explode("\n", trim($data));

			foreach ($data as $line) {
				if (($line = $this->csv_to_array($line)) === false) {
					return false;
				}
				array_push($this->data, $line);
			}
		}

		/* Return CSV data as array
		 *
		 * INPUT:  -
		 * OUTPUT: array csv data
		 * ERROR:  -
		 */
		public function to_array() {
			return $this->data;
		}

		/* Return CSV data as string
		 *
		 * INPUT:  -
		 * OUTPUT: string csv data
		 * ERROR:  -
		 */
		public function to_string() {
			$result = "";
			foreach ($this->data as $line) {
				foreach ($line as $i => $item) {
					$item = utf8_decode($item);
					if ((strpos($item, '"') !== false) || (strpos($item, ",") !== false) || (trim($item, " ") != $item)) {
						$line[$i] = '"'.str_replace('"', '""', $item).'"';
					} else {
						$line[$i] = $item;
					}
				}
				$result .= implode($this->seperator, $line)."\n";
			}

			if ($result === "") {
				$result = "\"\"\n";
			}

			return $result;
		}

		/* Write CSV file
		 *
		 * INPUT:  string csv file
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function write($file) {
			if (($fp = fopen($file, "w")) === false) {
				return false;
			}

			fputs($fp, $this->to_string());

			fclose($fp);

			return true;
		}
	}
?>
