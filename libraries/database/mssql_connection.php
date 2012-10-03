<?php
	/* libraries/database/mssql_connection.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class MSSQL_connection extends database_connection {
		public function __construct($hostname, $database, $username, $password) {
			$this->db_close         = "mssql_close";
			$this->db_escape_string = "addslashes";
			$this->db_query         = "mssql_query";
			$this->db_fetch         = "mssql_fetch_assoc";
			$this->db_free_result   = "mssql_free_result";
			$this->db_affected_rows = "mssql_rows_affected";
			$this->db_error         = array($this, "db_error_wrapper");
			$this->id_delim         = array("[", "]");

			if (($this->link = mssql_connect($hostname, $username, $password, true)) == false) {
				$this->link = null;
			} else if (mssql_select_db($database, $this->link) == false) {
				mssql_close($this->link);
				$this->link = null;
			}
		}

		protected function db_error_wrapper($link) {
			return mssql_get_last_message();
		}
	}
?>
