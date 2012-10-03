<?php
	/* libraries/pop3.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class POP3 {
		private $resource = null;
		private $message_count = null;
		private $mailbox_size = null;
		protected $port = 110;
		protected $protocol = "";

		/* Constructor
		 *
		 * INPUT:  string username, string password, string ip address
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($username, $password, $host = "127.0.0.1") {
			/* Connect
			 */
			if (($resource = fsockopen($this->protocol.$host, $this->port)) == false) {
				return;
			}
			$this->resource = $resource;

			$result = $this->read();
			if ($this->result_oke($result) == false) {
				$this->disconnect();
				return;
			}

			/* Send username
			 */
			$this->write("USER ".$username);
			$result = $this->read();
			if ($this->result_oke($result) == false) {
				$this->disconnect();
				return;
			}

			/* Send password
			 */
			$this->write("PASS ".$password);
			$result = $this->read();
			if ($this->result_oke($result) == false) {
				$this->disconnect();
				return;
			}

			/* Retreive
			 */
			$this->write("STAT");
			$result = $this->read();
			if ($this->result_oke($result) == false) {
				$this->disconnect();
				return;
			}
			list(, $this->message_count, $this->mailbox_size) = explode(" ", $result);
		}

		/* Destructor
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __destruct() {
			$this->disconnect();
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			switch ($key) {
				case "connected": return $this->resource !== null;
				case "message_count": return $this->message_count;
				case "mailbox_size": return $this->mailbox_size;
			}

			return null;
		}

		/* Disconnect from server
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		private function disconnect() {
			if ($this->resource !== null) {
				$this->write("QUIT");
				$this->read();
				fclose($this->resource);

				$this->resource = null;
			}
		}

		/* Result oke
		 *
		 * INPUT:  string result line
		 * OUTPUT: boolean result oke
		 * ERROR:  -
		 */
		private function result_oke($result) {
			return substr($result, 0, 3) == "+OK";
		}

		/* Read from resource
		 *
		 * INPUT:  -
		 * OUTPUT: string POP3 data
		 * ERROR:  false
		 */
		private function read() {
			return fgets($this->resource);
		}

		/* Write to resource
		 *
		 * INPUT:  string data
		 * OUTPUT: boolean write oke
		 * ERROR:  -
		 */
		private function write($message) {
			return fwrite($this->resource, $message."\r\n");
		}

		/* Write to resource
		 *
		 * INPUT:  int message identifier
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function delete_message($id) {
			if ($this->resource === null) {
				return false;
			}

			$this->write("DELE ".$id);
			$result = $this->read();

			return $this->result_oke($result);
		}

		/* Get message by id
		 *
		 * INPUT:  int message id
		 * OUTPUT: array( "header" => string message header, "body" => string message body )
		 * ERROR:  false
		 */
		public function get_message($id) {
			if ($this->resource === null) {
				return false;
			}

			if  (($id < 1) || ($id > $this->message_count)) {
				return false;
			}

			/* Fetch e-mail
			 */
			$this->write("RETR ".$id);
			$result = $this->read();

			$email = "";
			while (true) {
				if (($line = $this->read()) === false) {
					return false;
				}

				if ($line == ".\r\n") {
					break;
				}

				$email .= $line;
			}

			/* Parse e-mail
			 */
			list($head, $body) = explode("\r\n\r\n", $email, 2);
			$head = explode("\n", $head);
			$key = null;
			$header = array();
			foreach ($head as $value) {
				if (($value[0] != " ") && ($value[0] != "\t")) {
					if (($pos = strpos($value, ":")) !== false) {
						$key = strtolower(substr($value, 0, $pos));
						$value = substr($value, $pos + 1);
					}
					$i++;
				}

				$value = trim($value);
				if (isset($header[$key])) {
					$header[$key] .= " ".$value;
				} else {
					$header[$key] = $value;
				}
			}

			return array(
				"header" => $header,
				"body"   => $body);
		}
	}

	/* POP3 secured
	 */
	class POP3S extends POP3 {
		protected $port = 995;
		protected $protocol = "tls://";
	}
?>
