<?php
	/* libraries/database/sqlite3_connection.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class SQLite3_connection extends database_connection {
		public function __construct($filename, $mode = null, $encryption_key = null) {
			$this->db_close         = array($this, "db_close_wrapper");
			$this->db_insert_id     = array($this, "db_last_insert_wrapper");
			$this->db_escape_string = array($this, "db_escape_wrapper");
			$this->db_query         = array($this, "db_query_wrapper");
			$this->db_fetch         = array($this, "db_fetch_wrapper");
			$this->id_delim         = '"';

			$this->link = new SQLite3($filename, $mode, $encryption_key);
		}

		protected function db_close_wrapper() {
			if ($this->link !== null) {
				$this->link->close();
				$this->link = null;
			}
		}

		protected function db_last_insert_wrapper() {
			if ($this->link === null) {
				return false;
			}

			return $this->link->lastInsertRowID();
		}

		protected function db_escape_wrapper($string) {
			if ($this->link === null) {
				return false;
			}

			return $this->link->escapeString($string);
		}

		protected function db_query_wrapper($query) {
			if ($this->link === null) {
				return false;
			}

			return $this->link->query($query);
		}

		protected function db_fetch_wrapper($resource) {
			if ($this->link === null) {
				return false;
			}

			return $resource->fetchArray();
		}
	}
?>
