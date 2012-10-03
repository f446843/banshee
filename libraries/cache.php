<?php
	/* libraries/cache.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class cache {
		private $db = null;
		private $section = null;
		private $cache = array();

		/* Constructor
		 *
		 * INPUT:  object database, string section
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($db, $section) {
			$this->db = $db;
			$this->section = $section."_";
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($orig_key) {
			$key = $this->section.$orig_key;

			$now = time();

			/* In memory cache?
			 */
			if (isset($this->cache[$fkey])) {
				if ($now > $this->cache[$key]["timeout"]) {
					$this->delete($orig_key);
					return null;
				}

				return $this->cache[$key]["value"];
			}

			/* Fetch from database
			 */
			$query = "select value, UNIX_TIMESTAMP(timeout) as timeout ".
			         "from cache where %S=%s limit 1";
			if (($result = $this->db->execute($query, "key", $key)) == false) {
				return null;
			}

			$value = json_decode($result[0]["value"], true);
			$timeout = (int)$result[0]["timeout"];

			/* Timeout?
			 */
			if ($now > $timeout) {
				$this->delete($orig_key);
				return null;
			}

			$this->cache[$key] = array(
				"value"   => $value,
				"timeout" => $timeout);

			return $value;
		}

		/* Magic method set
		 *
		 * INPUT:  string key, string value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __set($key, $value) {
			$this->store($key, $value, CACHE_TIMEOUT);
		}

		/* Store data in cache
		 *
		 * INPUT:  string key, string value[, int timeout]
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function store($key, $value, $timeout = null) {
			if ($this->delete($key) == false) {
				return false;
			} else if ($value === null) {
				return true;
			}

			$key = $this->section.$key;

			if ($timeout === null) {
				$timeout = CACHE_TIMEOUT;
			} else if ($timeout <= 0) {
				return false;
			}
			$timeout += time();

			$data = array(
				"key"     => $key,
				"value"   => json_encode($value),
				"timeout" => date("Y-m-d H:i:s", $timeout));

			if ($this->db->insert("cache", $data) === false) {
				return false;
			}

			$this->cache[$key] = array(
				"value"   => $value,
				"timeout" => $timeout);

			return true;
		}

		/* Delete key
		 *
		 * INPUT:  string key
		 * OUTPUT: true
		 * ERROR:  false
		 */
		private function delete($key) {
			$key = $this->section.$key;

			if (isset($this->cache[$key])) {
				unset($this->cache[$key]);
			}

			$query = "delete from cache where %S=%s limit 1";
			return $this->db->query($query, "key", $key) !== false;
		}
	}
?>
