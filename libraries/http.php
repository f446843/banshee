<?php
	/* libraries/http.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class HTTP {
		protected $host = null;
		protected $port = null;
		protected $headers = array();
		protected $cookies = array();
		protected $via_proxy = false;
		protected $connect_host = null;
		protected $connect_port = null;
		protected $default_port = 80;
		protected $protocol = "";
		protected $timeout = 5;

		/* Constructor
		 *
		 * INPUT:  string host[, int port]
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($host, $port = null) {
			if ($port === null) {
				$port = $this->default_port;
			}

			$this->host = $this->connect_host = $host;
			$this->port = $this->connect_port = $port;
		}

		/* Magic method call
		 *
		 * INPUT:  string method, string URI[, string body]
		 * OUTPUT: array request result
		 * ERROR:  false
		 */
		public function __call($method, $parameters) {
			list($uri, $body) = $parameters;

			$methods = array("GET", "POST", "HEAD", "OPTIONS", "PUT", "DELETE", "TRACE");
			if (in_array($method, $methods) == false) {
				return false;
			}

			/* Method specific actions
			 */
			switch ($method) {
				case "POST":
					if (is_array($body)) {
						foreach ($body as $key => &$value) {
							$value = urlencode($key)."=".urlencode($value);
						}
						$body = implode("&", $body);
					}

					$this->add_header("Content-Length", strlen($body));
					$this->add_header("Content-Type", "application/x-www-form-urlencoded");
					break;
				case "PUT":
					$this->add_header("Content-Length", strlen($body));
					break;
				default:
					$body = "";
			}

			/* Add HTTP headers
			 */
			$this->add_header("Host", $this->host);
			$this->add_header("Connection", "close");
			$this->add_header("User-Agent", "Mozilla/5.0 (compatible; Banshee PHP framework HTTP library)");
			if (function_exists("gzdecode")) {
				$this->add_header("Accept-Encoding", "gzip");
			}

			/* Add cookies
			 */
			$cookies = array();
			foreach ($this->cookies as $key => $value) {
				array_push($cookies, $key."=".$value);
			}
			if (count($cookies) > 0) {
				$this->add_header("Cookie", implode("; ", $cookies));
			}

			/* Perform request
			 */
			if (($result = $this->perform_request($method, $uri, $body)) !== false) {
				$result = $this->parse_request_result($result);
			}

			$this->headers = array();

			return $result;
		}

		/* Send request via proxy
		 *
		 * INPUT:  string host, int port[, bool ssl]
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function via_proxy($host, $port, $ssl = false) {
			$this->connect_host = $host;
			$this->connect_port = $port;
			$this->protocol = $ssl ? "tls://" : "";
			$this->via_proxy = true;
		}

		/* Add HTTP header
		 *
		 * INPUT:  string key, string value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function add_header($key, $value) {
			$this->headers[$key] = $key.": ".$value;
		}

		/* Add cookie
		 *
		 * INPUT:  string key, string value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function add_cookie($key, $value) {
			if ($key != "") {
				$this->cookies[$key] = $value;
			}
		}

		/* Simulate AJAX for next request
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function simulate_ajax_request() {
			$this->add_header("X-Requested-With", "XMLHttpRequest");
		}

		/* Perform HTTP request
		 *
		 * INPUT:  string method, string uri[, string request body]
		 * OUTPUT: array( "status" => string status, "headers" => array HTTP headers, "body" => request body )
		 * ERROR:  false
		 */
		protected function perform_request($method, $url, $body = "") {
			if (($sock = @fsockopen($this->protocol.$this->connect_host, $this->connect_port, $errno, $errstr, $this->timeout)) == false) {
				return false;
			}

			/* Connect via proxy
			 */
			if ($this->via_proxy) {
				$protocol = $this->default_port == 80 ? "http" : "https";
				$port = $this->port != $this->default_port ? ":".$this->port : "";
				$url = sprintf("%s://%s%s%s", $protocol, $this->host, $port, $url);
			}

			/* Build and send request
			 */
			$headers = implode("\r\n", $this->headers);
			$request = sprintf("%s %s HTTP/1.1\r\n%s\r\n\r\n%s", $method, $url, $headers, $body);
			fputs($sock, $request);

			/* Read response
			 */
			$result = "";
			while (($line = fgets($sock)) !== false) {
				$result .= $line;
			}

			fclose($sock);

			return $result;
		}

		/* Parse request result
		 *
		 * INPUT:  string result
		 * OUTPUT: array result
		 * ERROR:  -
		 */
		protected function parse_request_result($result) {
			list($header, $body) = explode("\r\n\r\n", $result, 2);
			$header = explode("\r\n", $header);
			list(, $status) = explode(" ", $header[0]);

			$result = array(
				"status"  => (int)$status,
				"headers" => array(),
				"body"    => $body);

			/* Parse response headers
			 */
			$gzdecode = false;
			for ($i = 1; $i < count($header); $i++) {
				$parts = explode(":", $header[$i], 2);
				list($key, $value) = array_map("trim", $parts);
				$result["headers"][$key] = $value;

				if ($key == "Set-Cookie") {
					/* Cookie
					 */
					list($value) = explode(";", $value);
					list($cookie_key, $cookie_value) = explode("=", $value);
					$this->add_cookie($cookie_key, $cookie_value);
				} else if ($key == "Content-Encoding") {
					/* Content encoding
					 */
					if (strpos($value, "gzip") !== false) {
						$gzdecode = true;
					}
				} else if ($key == "Transfer-Encoding") {
					/* Transfer encoding
					 */
					if (strpos($value, "chunked") !== false) {
						$data = $result["body"];
						$result["body"] = "";

						do {
							list($size, $data) = explode("\r\n", $data, 2);
							$size = hexdec($size);

							if ($size > 0) {
								if (substr($data, $size, 2) != "\r\n") {
									$result["body"] = false;
									break;
								}
								$chunk = substr($data, 0, $size);
								$result["body"] .= $chunk;
								$data = substr($data, $size + 2);
							} else {
								break;
							}
						} while (strlen($data) > 0);
					}
				}
			}

			/* GZip content encoding
			 */
			if ($gzdecode) {
				$result["body"] = gzdecode($result["body"]);
			}

			return $result;
		}
	}

	/* Encrypted HTTP
	 */
	class HTTPS extends HTTP {
		protected $default_port = 443;
		protected $protocol = "tls://";
	}
?>
