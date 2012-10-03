<?php
	/* libraries/database/sqlite_connection.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class SQLite_connection extends database_connection {
		public function __construct($filename, $mode = null) {
			$this->db_close         = "sqlite_close";
			$this->db_insert_id     = "sqlite_last_insert_rowid";
			$this->db_escape_string = "sqlite_escape_string";
			$this->db_query         = "sqlite_query";
			$this->db_fetch         = array($this, "db_fetch_wrapper");
			$this->db_affected_rows = "sqlite_changes";
			$this->db_error         = array($this, "db_error_wrapper");
			$this->db_errno         = "sqlite_last_error";
			$this->id_delim         = '"';

			if (($this->link = sqlite_open($filename, $mode)) == false) {
				$this->link = null;
			}
		}

		protected function db_fetch_wrapper($resource) {
			if (in_array($resource, array(null, false, true), true)) {
				$result = false;
			} else if (($result = sqlite_fetch_array($resource, SQLITE_ASSOC)) === null) {
				$result = false;
			}

			return $result;
		}

		protected function db_error_wrapper($db_handle) {
			return sqlite_error_string(sqlite_last_error($db_handle));
		}
	}
?>
