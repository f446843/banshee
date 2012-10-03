<?php
	/* libraries/session.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 *
	 * Don't change this file, unless you know what you are doing.
	 */

	final class session {
		private $db = null;
		private $id = null;
		private $session_id = null;
		private $use_database = null;

		/* Constructor
		 *
		 * INPUT:  object database
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($db) {
			$this->db = $db;

			if ($_SERVER["HTTP_X_BANSHEE_SESSION"] == "disk") {
				$this->use_database = false;
			} else {
				$this->use_database = (SESSION_TIMEOUT >= ini_get("session.gc_maxlifetime"));
			}

			if ($this->use_database) {
				$this->db->query("delete from sessions where expire<=now()");
			}

			$this->start();
		}

		/* Destructor
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __destruct() {
			if ($this->use_database == false) {
				session_write_close();
				return;
			} else if ($this->id === null) {
				return;
			} else if ($this->db->connected == false) {
				return;
			}

			$session_data = array(
				"content"    => json_encode($_SESSION),
				"ip_address" => $_SERVER["REMOTE_ADDR"]);
			if (is_true(SESSION_PERSISTENT) == false) {
				$session_data["expire"] = date("Y-m-d H:i:s", time() + SESSION_TIMEOUT);
			}

			$this->db->update("sessions", $this->id, $session_data);

			$_SESSION = array();
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			switch ($key) {
				case "using_database": return $this->use_database;
			}

			return null;
		}

		/* Start session
		 *
		 * INPUT:  -
		 * OUTPUT: true
		 * ERROR:  false
		 */
		private function start() {
			if ($this->use_database) {
				/* Use database
				 */
				$query = "select * from sessions where session_id=%s";

				if (isset($_COOKIE[SESSION_NAME]) == false) {
					/* New session
					 */
					if ($this->new_session() == false) {
						return false;
					}
				} else if (($sessions = $this->db->execute($query, $_COOKIE[SESSION_NAME])) != false) {
					/* Existing session
					 */
					$this->id = (int)$sessions[0]["id"];
					$this->session_id = $_COOKIE[SESSION_NAME];
					$_SESSION = json_decode($sessions[0]["content"], true);
				} else {
					/* Unknown session
					 */
					if ($this->new_session() == false) {
						return false;
					}
				}
			} else {
				/* Use PHP's session handling 
				 */
				session_name(SESSION_NAME);
				if (is_true(SESSION_PERSISTENT)) {
					session_set_cookie_params(SESSION_TIMEOUT);
				}
				if (session_start() == false) {
					return false;
				}
				$this->session_id = session_id();
			}

			return true;
		}

		/* Start a new session stored in the database
		 *
		 * INPUT;  -
		 * OUTPUT: true
		 * ERROR:  false
		 */
		private function new_session() {
			/* Create new session id
			 */
			$attempts = 3;
			$query = "select id from sessions where session_id=%s";

			do {
				if ($attempts-- == 0) {
					return false;
				}

				$session_id = random_string();

				if (($result = $this->db->execute($query, $session_id)) === false) {
					return false;
				}
			} while ($result != false);

			/* Store session in database
			 */
			$session_data = array(
				"id"         => null,
				"session_id" => $session_id,
				"content"    => null,
				"expire"     => date("Y-m-d H:i:s", time() + SESSION_TIMEOUT),
				"user_id"    => null,
				"ip_address" => $_SERVER["REMOTE_ADDR"],
				"name"       => null);

			if ($this->db->insert("sessions", $session_data) === false) {
				return false;
			}

			$this->id = $this->db->last_insert_id;
			$this->session_id = $session_id;

			/* Place session id in cookie
			 */
			$timeout = is_true(SESSION_PERSISTENT) ? time() + SESSION_TIMEOUT : null;
			setcookie(SESSION_NAME, $this->session_id, $timeout, "/");
			$_COOKIE[SESSION_NAME] = $this->session_id;

			return true;
		}

		/* Update user_id in session record
		 *
		 * INPUT:  int user id
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function set_user_id($user_id) {
			if ($this->use_database == false) {
				return true;
			} else if ($this->id === null) {
				return false;
			}

			$user_data = array("user_id" => (int)$user_id);

			return $this->db->update("sessions", $this->id, $user_data) !== false;
		}

		/* Reset session
		 *
		 * INPUT:  -
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function reset() {
			unset($_COOKIE[SESSION_NAME]);
			$_SESSION = array();
			if ($this->use_database) {
				$this->db->query("delete from sessions where id=%d", $this->id);
			} else {
				session_unset();
				session_destroy();
			}

			return $this->start();
		}
	}
?>
