<?php
	class banshee_sso_controller extends controller {
		/* Get login key of user at remote website
		 */
		private function website($index) {
			global $sso_websites;

			if ($index == null) {
				return;
			} else if ($index >= count($sso_websites)) {
				return;
			}

			if (is_false($sso_websites[$index]["ssl"])) {
				$website = new banshee_website($sso_websites[$index]["server"], $sso_websites[$index]["port"]);
			} else {
				$website = new banshee_website_ssl($sso_websites[$index]["server"], $sso_websites[$index]["port"]);
			}

			if ($website->login($sso_websites[$index]["username"], $sso_websites[$index]["password"]) == false) {
				return;
			}
			$website->simulate_ajax_request();
			$result = $website->GET("/system/sso/user/".$this->user->username);
			if (($key = $website->array_path($result, "/output/key")) === false) {
				return;
			}

			$protocol = is_false($sso_websites[$index]["ssl"]) ? "http" : "https";
			$port = isset($sso_websites[$index]["port"]) ? ":".$sso_websites[$index]["port"] : "";

			$this->output->add_tag("login", sprintf("%s://%s%s/system/sso/image?login=%s", $protocol, $sso_websites[$index]["server"], $port, $key));
		}

		/* Get login key of user
		 */
		private function user($username) {
			global $sso_websites, $sso_servers, $sso_username;

			if (($this->user->logged_in == false) || ($username == null)) {
				return;
			} else if (in_array($_SERVER["REMOTE_ADDR"], $sso_servers, true) == false) {
				return false;
			} else if ($this->user->username !== $sso_username) {
				return;
			} else if (($user_id = $this->model->get_user_id($username)) === false) {
				return;
			}

			$this->output->add_tag("key", one_time_key($this->db, $user_id));
		}

		/* Output dummy image (single pixel)
		 */
		private function image() {
			header("Content-Type: image/gif");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Pragma: no-cache");

			print "\x47\x49\x46\x38\x39\x61\x01\x00\x01\x00";
			print "\x91\x00\x00\x00\x00\x00\xff\xff\xff\xff";
			print "\xff\xff\x00\x00\x00\x21\xf9\x04\x01\x00";
			print "\x00\x02\x00\x2c\x00\x00\x00\x00\x01\x00";
			print "\x01\x00\x00\x02\x02\x4c\x01\x00\x3b";

			$this->output->disable();
		}

		/* Execute
		 */
		public function execute() {
			if ($this->page->ajax_request == false) {
				$this->output->disable();
			}

			switch ($this->page->pathinfo[2]) {
				case "website":
					$this->website($this->page->pathinfo[3]);
					break;
				case "user":
					$this->user($this->page->pathinfo[3]);
					break;
				case "image":
					$this->image();
					break;
			}
		}
	}
?>
