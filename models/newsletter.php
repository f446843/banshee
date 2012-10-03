<?php
	class newsletter_model extends model {
		private function generate_code($email) {
			$timestamp = date("YmdHis", strtotime("+".$this->settings->newsletter_code_timeout));

			return $timestamp."-".md5($email.$this->settings->secret_website_code.$timestamp);
		}

		public function verify_code($email, $code) {
			$email = strtolower($email);

			list($timestamp, $code) = explode("-", $code, 2);

			if ((int)$timestamp < (int)date("YmdHis")) {
				return false;
			}

			return $code == md5($email.$this->settings->secret_website_code.$timestamp);
		}

		public function info_oke($info) {
			$info["email"] = strtolower($info["email"]);

			if (valid_email($info["email"]) == false) {
				$this->output->add_message("Invalid e-mail address");
				return false;
			}

			return true;
		}

		public function ask_confirmation($info, $subscribe) {	
			$info["email"] = strtolower($info["email"]);

			$query = "select count(*) as count from subscriptions where email=%s";
			if (($result = $this->db->execute($query, $info["email"])) == false) {
				return false;
			}
			$count = $result[0]["count"];
			
			if ($subscribe) {
				/* Subscribe
				 */
				if ($count == 1) {
					return true;
				}
				$title = "subscription";
				$mode = "subscribe";
				$action = "subscribe to";
			} else {
				/* Unsubscribe
				 */
				if ($count == 0) {
					return true;
				}
				$title = "unsubscription";
				$mode = "unsubscribe";
				$action = "unsubscribe from";
			}

			$newsletter = new newsletter("Confirm ".$this->settings->head_title." newsletter ".$title, $this->settings->newsletter_email, $this->settings->newsletter_name);
			$url = "http://".$_SERVER["SERVER_NAME"]."/newsletter?".$mode."=".$info["email"]."&code=".$this->generate_code($info["email"]);
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
