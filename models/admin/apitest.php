<?php
	class admin_apitest_model extends model {
		private function indent_json($json) {
			$result      = "";
			$pos         = 0;
			$json_len    = strlen($json);
			$indent      = "  ";
			$newline     = "\n";
			$prev_char   = "";
			$no_quotes   = true;

			for ($i = 0; $i <= $json_len; $i++) {
				$char = substr($json, $i, 1);

				if ($char == '"' && $prev_char != "\\") {
					$no_quotes = !$no_quotes;
				} else if(($char == "}" || $char == "]") && $no_quotes) {
					$result .= $newline;
					$pos --;
					for ($j = 0; $j < $pos; $j++) {
						$result .= $indent;
					}
				}

				$result .= $char;

				if (($char == "," || $char == "{" || $char == "[") && $no_quotes) {
					$result .= $newline;
					if ($char == "{" || $char == "[") {
						$pos ++;
					}

					for ($j = 0; $j < $pos; $j++) {
						$result .= $indent;
					}
				}

				$prev_char = $char;
			}

			return $result;
		}

		public function request_result($data) {
			if ($data["url"][0] != "/") {
				return false;
			}

			if ($_SERVER["HTTPS"] == "on") {
				$http = new HTTPS($_SERVER["HTTP_HOST"]);
			} else {
				$http = new HTTP($_SERVER["HTTP_HOST"]);
			}

			/* Determine URl path
			 */
			$url = $data["url"];
			if (strpos($url, "?") === false) {
				$url .= "?";
			} else {
				$url .= "&";
			}
			$url .= "output=";

			switch ($data["type"]) {
				case "ajax": $url .= "ajax"; break;
				case "xml": $url .= "restxml"; break;
				case "json": $url .= "restjson"; break;
				default: return false;
			}

			/* Restore cookies
			 */
			if (isset($_SESSION["apitest_cookies"])) {
				if (($cookies = json_decode($_SESSION["apitest_cookies"], true)) !== null) {
					foreach ($cookies as $key => $value) {	
						$http->add_cookie($key, $value);
					}
				}
			}

			/* Authentication
			 */
			if (($data["username"] != "") && ($data["password"] != "")) {
				$auth_str = sprintf("%s:%s", $data["username"], $data["password"]);
				$http->add_header("Authorization", "Basic ".base64_encode($auth_str));
			}

			/* Send request
			 */
			switch ($data["method"]) {
				case "GET": $result = $http->GET($url); break;
				case "POST": $result = $http->POST($url, $data["postdata"]); break;
				case "PUT": $result = $http->PUT($url, $data["postdata"]); break;
				case "DELETE": $result = $http->DELETE($url); break;
				default: return false;
			}

			/* Decode JSON result
			 */
			if ($result["headers"]["Content-Type"] == "application/json") {
				$result["body"] = $this->indent_json($result["body"]);
			}

			/* Store cookies
			 */
			$_SESSION["apitest_cookies"] = json_encode($http->cookies);

			return $result;
		}
	}
?>
