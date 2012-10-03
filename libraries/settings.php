<?php
	/* libraries/settings.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 *
	 * Don't change this file, unless you know what you are doing.
	 */

	class settings {
		private $db = null;
		private $max_value_len = 256;
		private $cache = array();
		private $types = array("boolean", "integer", "string");

		/* Constructor
		 *
		 * INPUT:  object database
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($db) {
			global $preload_settings;

			$this->db = $db;

			/* Preload often used settings
			 */
			if (($count = count($preload_settings)) == 0) {
				return;
			}

			$query = "select * from settings where %S in (".implode(", ", array_fill(1, $count, "%s")).")";
			if (($settings = $db->execute($query, "key", $preload_settings)) == false) {
				return;
			}

			foreach ($settings as $setting) {
				$value = $setting["value"];
				switch ($setting["type"]) {
					case "integer": $value = (int)$value; break;
					case "boolean": $value = is_true($value); break;
				}
				$this->cache[$setting["key"]] = $value;
			}
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			if ($this->valid_key($key) == false) {
				return null;
			}
			
			if (isset($this->cache[$key])) {
				return $this->cache[$key];
			}

			if ($this->db->connected == false) {
				return null;
			}
			
			$query = "select * from settings where %S=%s";
			if (($setting = $this->db->execute($query, "key", $key)) === false) {
				return null;
			} else if (count($setting) == 0) {
				return null;
			}

			$value = $setting[0]["value"];
			switch ($setting[0]["type"]) {
				case "integer": $value = (int)$value; break;
				case "boolean": $value = is_true($value); break;
			}

			$this->cache[$key] = $value;

			return $value;
		}

		/* Set setting
		 *
		 * INPUT:  string key, mixed value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __set($key, $value) {
			if ($this->valid_key($key) == false) {
				return;
			}

			if ($value === null) {	
				$query = "delete from settings where %S=%s";
				if ($this->db->query($query, "key", $key) !== false) {
					unset($this->cache[$key]);
				}
			} else if (is_int($value)) {
				$this->store($key, "integer", (string)$value);
			} else if (is_bool($value)) {
				$this->store($key, "boolean", show_boolean($value));
			} else if (is_string($value)) {
				$this->store($key, "string", $value);
			}
		}

		/* Store seting in database
		 *
		 * INPUT:  string key, string type, mixed value
		 * OUTPUT: true
		 * ERROR:  false
		 */
		private function store($key, $type, $value) {
			if (strlen($value) > $this->max_value_len) {
				return false;
			}

			if ($this->__get($key) === null) {
				$query = "insert into settings (%S, %S, %S) values (%s, %s, %s)";
				$result = $this->db->query($query, "key", "type", "value", $key, $type, $value);
			} else {
				$query = "update settings set %S=%s, %S=%s where %S=%s";
				$result = $this->db->query($query, "type", $type, "value", $value, "key", $key);
			}

			if ($result === false) {
				return false;
			}

			$this->cache[$key] = $value;
			return true;
		}

		/* Check key name validity
		 *
		 * INPUT:  string key
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function valid_key($key) {
			return valid_input($key, VALIDATE_LETTERS.VALIDATE_NUMBERS."-_", VALIDATE_NONEMPTY);
		}

		/* Return supported variable types
		 *
		 * INPUT:  -
		 * OUTPUT: array variable types
		 * ERROR:  -
		 */
		public function supported_types() {
			return $this->types;
		}
	}
?>
