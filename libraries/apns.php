<?php
	class apns {
		private $host = "gateway.push.apple.com";
		private $port = 2195;
		private $certificate = null;
		private $notifications = array();

		/* Constructor
		 *
		 * INPUT:  string certificate file
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($certificate) {
			$this->certificate = $certificate;
		}

		/* Destructor
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __destruct() {
			$this->send();
		}

		/* Add notification to queue
		 *
		 * INPUT:  mixed device token, string content
		 * OUTPUT: boolean successful
		 * ERROR:  -
		 */
		public function send_notification($tokens, $content) {
			$content = json_encode(array("aps" => $content));
			if (($conlen = strlen($content)) > 256) {
				print "APNS: content too long.\n";
				return false;
			}

			if (is_array($tokens) == false) {
				$tokens = array($tokens);
			}

			foreach ($tokens as $token) {
				if (($toklen = strlen($token)) != 64) {
					print "APNS: invalid token.\n";
					continue;
				}

				$notification = chr(0).
					chr(0).chr(32).pack("H*", $token).
					chr(0).chr($conlen).$content;
				array_push($this->notifications, $notification);
			}

			return true;
		}

		/* Send the notifications
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		private function send() {
			if (count($this->notifications) == 0) {
				return;
			}

			$context = stream_context_create();
			if (stream_context_set_option($context, "ssl", "local_cert", $this->certificate) === false) {
				print "APNS: error setting stream context option.\n";
				return;
			}

			$uri = "ssl://".$this->host.":".$this->port;
			if (($socket = stream_socket_client($uri, $errno, $error, 3, STREAM_CLIENT_CONNECT, $context)) == false) {
				print "APNS: error creating socket.\n";
				return;
			}

			foreach ($this->notifications as $notification) {
				fwrite($socket, $notification);
			}

			fclose($socket);
		}
	}
?>
