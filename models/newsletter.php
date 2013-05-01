<?php
	class newsletter_model extends model {
		private function signature($data) {
			return md5(implode("|", $data)."|".$this->settings->secret_website_code);
		}

		public function extract_data($data) {
			$data = strtr($data, "_-", "/+");

			if (($data = base64_decode($data, false)) === false) {
				return false;
			} else if (($data = json_decode($data, true)) === null) {
				return false;
			}
			
			if ((int)$data["expires"] < (int)date("YmdHis")) {
				return false;
			}

			$signature = $data["signature"];
			unset($data["signature"]);
			if ($this->signature($data) != $signature) {
				return false;
			}

			return $data;
		}

		public function info_oke($info) {
			$info["email"] = strtolower($info["email"]);

			if (valid_email($info["email"]) == false) {
				$this->output->add_message("Invalid e-mail address");
				return false;
			}

			return true;
		}

		public function ask_confirmation($info, $mode) {
			$info["email"] = strtolower($info["email"]);

			$query = "select count(*) as count from subscriptions where email=%s";
			if (($result = $this->db->execute($query, $info["email"])) === false) {
				return false;
			}
			$count = $result[0]["count"];

			if ($mode == "subscribe") {
				/* Subscribe
				 */
				if ($count == 1) {
					return true;
				}
				$title = "subscription";
				$action = "subscribe to";
			} else if ($mode == "unsubscribe") {
				/* Unsubscribe
				 */
				if ($count == 0) {
					return true;
				}
				$title = "unsubscription";
				$action = "unsubscribe from";
			} else {	
				return false;
			}

			$data = array(
				"mode"    => $mode,
				"email"   => $info["email"],
				"expires" => date("YmdHis", strtotime("+".$this->settings->newsletter_code_timeout)));
			$data["signature"] = $this->signature($data);
			$code = base64_encode(json_encode($data));
			$code = strtr($code, "/+", "_-");

			$subject = "Confirm ".$this->settings->head_title." newsletter ".$title;
			$newsletter = new newsletter($subject, $this->settings->newsletter_email, $this->settings->newsletter_name);
			$url = "http://".$_SERVER["SERVER_NAME"]."/newsletter/".$code;
			$message  = "You recieve this e-mail because your e-mail address has been entered at the newsletter ".
						"form on the ".$this->settings->head_title." website. Subscribing to or unsubscribing from this ".
						"newsletter list requires confirmation. So, if you do want to ".$action." the ".$this->settings->head_title." ".
						"newsletter list, confirm by following <a href=\"".$url."\">this link</a>. If that's not ".
						"what you want, just ignore this e-mail.\n";
			$newsletter->message($message);

			return $newsletter->send($info["email"]);
		}

		public function subscribe($email) {
			$email = strtolower($email);

			$query = "select count(*) as count from subscriptions where email=%s";
			if (($result = $this->db->execute($query, $email)) == false) {
				return false;
			} else if ($result[0]["count"] == 1) {
				return false;
			}

			$info["id"] = null;
			$info["email"] = $email;

			return $this->db->insert("subscriptions", $info) !== false;
		}

		public function unsubscribe($email) {
			$email = strtolower($email);

			return $this->db->query("delete from subscriptions where email=%s", $email) !== false;
		}
	}
?>
