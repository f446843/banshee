<?php
	/* libraries/database/postgresql_connection.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class PostgreSQL_connection extends database_connection {
		public function __construct($hostname, $database, $username, $password, $port = 5432) {
			$this->db_close         = "pg_close";
			$this->db_insert_id     = "pg_last_oid";
			$this->db_escape_string = array($this, "db_escape_string_wrapper");
			$this->db_query         = "pg_query";
			$this->db_fetch         = "pg_fetch_assoc";
			$this->db_free_result   = "pg_free_result";
			$this->db_affected_rows = "pg_affected_rows";
			$this->db_error         = "pg_last_error";
			$this->id_delim         = '"';

			if (($this->link = pg_connect("host=".$hostname." port=".$port." dbname=".$database." user=".$username." password=".$password)) == false) {
				$this->link = null;
			}
		}

		protected function db_escape_string_wrapper($str) {
			return pg_escape_string($this->link, $str);
		}

	}
?>
