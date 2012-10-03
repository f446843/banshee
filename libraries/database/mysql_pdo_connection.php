<?php
	/* libraries/database/mysql_pdo_connection.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class MySQL_PDO_connection extends PDO_connection {
		protected $type = "mysql";
		protected $id_delim = "`";
		protected $options = array(
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
			PDO::ATTR_EMULATE_PREPARES         => true);

		public function __construct() {
			$args = func_get_args();
			call_user_func_array(array(parent, "__construct"), $args);

			if ($this->link !== null) {
				$this->query("set names %s", "utf8");
			}
		}
	}
?>
