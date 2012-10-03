<?php
	/* libraries/database/mysql_connection.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class MySQL_connection extends database_connection {
		public function __construct($hostname, $database, $username, $password) {
			$this->db_close         = "mysql_close";
			$this->db_insert_id     = "mysql_insert_id";
			$this->db_escape_string = array($this, "db_escape_string_wrapper");
			$this->db_query         = "mysql_query";
			$this->db_fetch         = "mysql_fetch_assoc";
			$this->db_free_result   = "mysql_free_result";
			$this->db_affected_rows = "mysql_affected_rows";
			$this->db_error         = "mysql_error";
			$this->db_errno         = "mysql_errno";
			$this->id_delim         = "`";

			if (($this->link = mysql_connect($hostname, $username, $password, true)) == false) {
				$this->link = null;
			} else if (mysql_select_db($database, $this->link) == false) {
				mysql_close($this->link);
				$this->link = null;
			} else {
				$this->query("set names %s", "utf8");
			}
		}

		protected function db_escape_string_wrapper($str) {
			return mysql_real_escape_string($str, $this->link);
		}
	}
?>
