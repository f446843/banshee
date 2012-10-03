<?php
	class admin_newsletter_model extends model {
		public function newsletter_oke($info) {
			$result = true;

			if ($info["title"] == "") {
				$this->output->add_message("No title");
				$result = false;
			}

			if ($info["content"] == "") {
				$this->output->add_message("No content");
				$result = false;
			}

			return $result;
		}

		public function send_newsletter($info) {
			$newsletter = new newsletter($info["title"], $this->settings->newsletter_email, $this->settings->newsletter_name);
			$newsletter->message($info["content"]);

			$query = "select * from subscriptions";
			if (($subscribers = $this->db->execute($query)) == false) {
				return false;
			}

			$chunks = array_chunk($subscribers, $this->settings->newsletter_bcc_size);

			foreach ($chunks as $subscribers) {
				foreach ($subscribers as $subscriber) {
					$newsletter->bcc($subscriber["email"]);
				}

				if ($newsletter->send($this->settings->newsletter_email, $this->settings->newsletter_name) == false) {
					return false;
				}
			}

			return true;
		}

		public function preview_newsletter($info) {
			$newsletter = new newsletter($info["title"], $this->settings->newsletter_email, $this->settings->newsletter_name);
			$newsletter->message($info["content"]);

			return $newsletter->send($this->user->email);
		}
	}
?>
