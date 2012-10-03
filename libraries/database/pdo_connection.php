<?php
	/* libraries/database/pdo_connection.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 *
	 * Don't change this file, unless you know what you are doing.
	 */

	abstract class PDO_connection extends database_connection {
		protected $type = null;
		protected $options = null;
		private $last_query = null;

		public function __construct($hostname, $database, $username, $password) {
			$this->db_close         = array($this, "db_close_wrapper");
			$this->db_insert_id     = array($this, "db_insert_id_wrapper");
			$this->db_escape_string = array($this, "db_escape_wrapper");
			$this->db_fetch         = array($this, "db_fetch_wrapper");
			$this->db_affected_rows = array($this, "db_affected_rows_wrapper");
			$this->db_error         = array($this, "db_error_wrapper");
			$this->db_errno         = array($this, "db_errno_wrapper");

			try {
				$this->link = new PDO($this->type.":host=".$hostname.";dbname=".$database, $username, $password, $this->options);
			} catch (exception $e) {
				$this->link = null;
			}
		}

		protected function db_close_wrapper() {
			$this->link = null;
		}

		protected function db_insert_id_wrapper() {
			return $this->link->lastInsertId();
		}

		protected function db_escape_wrapper($string) {
			return substr($this->link->quote((string)$string), 1, -1);
		}

		public function query() {
			if ($this->connected == false) {
				print "Not connected to database!\n";
				return false;
			} else if (func_num_args() == 0) {
				return false;
			}

			$this->last_query = null;

			$args = func_get_args();
			$format = array_shift($args);
			$values = $this->flatten_array($args);
			unset($args);

			$query_lower = strtolower(trim($format));
			if (($query_lower == "begin") || ($query_lower == "start transaction")) {
				/* Start transaction
				 */
				try {
					return $this->link->beginTransaction();
				} catch (exception $e) {
					return false;
				}
			} else if ($query_lower == "rollback") {
				/* Rollback transaction
				 */
				try {
					return $this->link->rollBack();
				} catch (exception $e) {
					return false;
				}
			} else if ($query_lower == "commit" ) {
				/* Commit transaction
				 */
				try {
					return $this->link->commit();
				} catch (exception $e) {
					return false;
				}
			} else {
				/* Other queries
				 */

				/* -----------------------------------------------------------
				 * Work-around for not being able to delimit an indentifier
				 * via PDOStatement::bindValue()
				 */
				$offset = 0;
				$nr = 0;
				while (($pos = strpos($format, "%", $offset)) !== false) {
					if ($format[$pos + 1] == "S") {
						$identifier = $this->delimit_identifier($values[$nr]);
						$format = substr($format, 0, $pos).$identifier.substr($format, $pos + 2);
						$pos += strlen($values[$nr]);
						unset($values[$nr]);
					}

					if ($format[$pos + 1] == "%") {
						$pos++;
					} else {
						$nr++;
					}

					$offset = $pos + 1;
				}
				$values = array_values($values);
				/* -----------------------------------------------------------
				 */

				try {
					$query = str_replace(array("%d", "%f", "%s", "%S"), "?", $format);
					$resource = $this->link->prepare($query);
				} catch (exception $e) {
					return false;
				}

				$offset = 0;
				$nr = 0;
				while (($pos = strpos($format, "%", $offset)) !== false) {
					if ($format[$pos + 1] == "d") {
						// Integer
						$resource->bindValue($nr + 1, (int)$values[$nr], PDO::PARAM_INT);
					} else if ($format[$pos + 1] == "s") {
						// String
						$resource->bindValue($nr + 1, (string)$values[$nr], PDO::PARAM_STR);
					} else if ($format[$pos + 1] == "S") {
						// Identifier
						$resource->bindValue($nr + 1, (string)$values[$nr], PDO::PARAM_STR);
					} else {
						return false;
					}

					$offset = $pos + 1;
					$nr++;
				}

				$this->last_query = $resource;
				if ($resource->execute() === false) {
					return false;
				}

				return $resource;
			}
		}

		protected function db_fetch_wrapper($resource) {
			return $resource->fetch(PDO::FETCH_ASSOC);
		}

		protected function db_affected_rows_wrapper() {
			if ($this->last_query === null) {
				return -1;
			}

			return $this->last_query->rowCount();
		}

		protected function db_error_wrapper() {
			return implode("\n", $this->link->errorInfo());
		}

		protected function db_errno_wrapper() {
			return $this->link->errorCode();
		}
	}
?>
