<?php
	/* libraries/banshee_website.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class banshee_website extends HTTP {
		/* Magic method call
		 *
		 * INPUT:  string method, string URI[, string body]
		 * OUTPUT: array request result
		 * ERROR:  false
		 */
		public function __call($method, $parameters) {
			list($uri, $body) = $parameters;
			list($path, $parameters) = explode("?", $uri);

			/* Set output parameter to xml
			 */
			$parameters = explode("&", $parameters);
			$found = false;
			foreach ($parameters as &$parameter) {
				list($key, $value) = explode("=", $parameter);
				if ($key == "output") {
					$parameter = "output=xml";
					$found = true;
					break;
				}
			}
			if ($found == false) {
				array_push($parameters, "output=xml");
				$output_xml = true;
			}

			$this->add_header("X-Banshee-Session", "disk");

			/* Make HTTP call
			 */
			$uri = $path."?".implode("&", $parameters);
			$result = parent::__call($method, array($uri, $body));

			if ($result["status"] != 200) {
				return false;
			}

			$xml = new XML;

			return $xml->xml_to_array($result["body"]);
		}

		/* Login to Banshee based website
		 *
		 * INPUT:  string username, string password
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function login($username, $password) {
			if ($this->protocol == "tls://") {
				/* Generate POST data
				 */
				$post_data = implode("&", array(
					"username=".urlencode($username),
					"password=".urlencode($password),
					"use_cr_method=no"));
			} else {
				/* Retreive challenge
				 */
				$result = $this->__call("GET", array("/login"));
				if (($challenge = $this->array_path($result, "/output/content/login/challenge")) === false) {
					return false;
				}

				if (($version = $this->array_path($result, "/output/banshee_version")) === false) {
					return false;
				}

				/* Generate POST data
				 */
				$data = $password;
				$hash_func = $version >= 3.5 ? PASSWORD_HASH : "md5";
				if ($version >= 3.4) {
					$data .= hash($hash_func, $username);
				} else if ($version >= 3.1) {
					$data .= $username;
				}

				$post_data = implode("&", array(
					"username=".urlencode($username),
					"password=".hash($hash_func, $challenge.hash($hash_func, $data)),
					"use_cr_method=yes"));
			}

			/* Send login
			 */
			$result = $this->__call("POST", array("/login", $post_data));

			/* Return result
			 */
			if ($this->array_path($result, "/output/page") != "login") {
				$authenticated = true;
			} else {
				$authenticated = $this->array_path($result, "/output/user") != false;
			}

			return $authenticated;
		}

		/* Logout of Banshee based website
		 *
		 * INPUT:  -
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function logout() {
			$result = $this->__call("GET", array("/logout"));

			return $result !== false;
		}

		/* Get array element by path
		 *
		 * INPUT:  array xml data, string array path
		 * OUTPUT: string tag content
		 * ERROR:  false
		 */
		public function array_path($array, $path) {
			$path = explode("/", trim($path, "/"));

			while (($part = array_shift($path)) !== null) {
				if (is_array($array) == false) {
					return false;
				}

				foreach ($array as $item) {
					if ($item["name"] == $part) {
						$array = $item["content"];
						continue 2;
					}
				}

				return false;
			}

			return $array;
		}
	}

	class banshee_website_ssl extends banshee_website {
		protected $default_port = 443;
		protected $protocol = "tls://";
	}
?>
