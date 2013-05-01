<?php
	/* Perform HTTP GET request and follow redirects
	 *
	 * INPUT:  string URL[, int redirect limit]
	 * OUTPUT: array HTTP result
	 * ERROR:  false
	 */
	function follow_http_redirects($url, $redirects = 5) {
		$result = false;
		$referer = null;

		while ($redirects-- >= 0) {
			list($protocol,, $hostname, $path) = explode("/", $url, 4);

			if ($protocol == "http:") {
				$http = new http($hostname);
			} else if ($protocol == "https:") {
				$http = new https($hostname);
			} else {
				break;
			}

			if ($referer != null) {
				$http->add_header("Referer", $referer);
			}
			$result = $http->GET("/".$path);
			unset($http);

			$referer = $url;

			if ($result === false) {
				break;
			} else if (($result["status"] != 301) && ($result["status"] != 302)) {
				break;
			} else if (($url = $result["headers"]["Location"]) == "") {
				break;
			}
		}

		return $result;
	}
?>
