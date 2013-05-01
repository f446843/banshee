<?php
	/* Exception handler
	 *
	 * INPUT:  error object
	 * OUTPUT: -
	 * ERROR:  -
	 */
	function exception_handler($error) {
		print "Caught exception '".$error->getmessage()."'<br />\n";
	}

	/* Error handler
	 *
	 * INPUT:  int error number, string error string, string filename, int line number
	 * OUTPUT: -
	 * ERROR:  -
	 */
	function error_handler($errno, $errstr, $errfile, $errline) {
		print $errstr." in ".$errfile." on line ".$errline.".<br />\n";

		return true;
	}

	/* Website error handler class
	 */
	final class website_error_handler {
		private $output = null;
		private $settings = null;
		private $user = null;

		/* Constructor
		 *
		 * INPUT:  object output, object settings, object user
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($output, $settings, $user) {
			$this->output = $output;
			$this->settings = $settings;
			$this->user = $user;
		}

		/* Add errors to output
		 *
		 * INPUT:  string errors
		 * OUTPUT: -
		 * ERROR:  -
		 */
		private function add_to_output($errors) {
			$errors = htmlentities($errors);
			$errors = str_replace("\t", "    ", $errors);
			$errors = explode("\n", $errors);

			$result = "";
			foreach ($errors as $error) {
				$len = strlen($error);
				$error = ltrim($error);
				if (($len = $len - strlen($error)) > 0) {
					$result .= str_repeat("&nbsp;", $len);
				}
				$result .= $error."<br />\n";
			}

			$this->output->add_tag("internal_errors", $result);
		}

		/* Send errors via e-mail to webmaster
		 *
		 * INPUT:  string errors
		 * OUTPUT: -
		 * ERROR:  -
		 */
		private function send_via_email($errors) {
			$message =
				"Date, time: ".date("j F Y, H:i:s")."\n".
				"Used URL  : ".$_SERVER["REQUEST_URI"]."\n".
				"IP address: ".$_SERVER["REMOTE_ADDR"]."\n".
				"Username  : ".($this->user->username != null ? $this->user->username."\n" : "-\n").
				"User-Agent: ".$_SERVER["HTTP_USER_AGENT"]."\n".
				"\n".$errors;

			$email = new email("Internal error at ".$_SERVER["SERVER_NAME"], "no-reply@".$_SERVER["SERVER_NAME"]);
			$email->message($message);
			$email->send($this->settings->webmaster_email);
			unset($email);
		}

		/* Handle website errors
		 *
		 * INPUT:  string errors
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function execute($errors) {
			$errors = str_replace("<br />", "", trim($errors));
			if (is_true(DEBUG_MODE)) {
				$this->add_to_output($errors);
			} else {
				$this->send_via_email($errors);
			}
		}
	}

	/* Error handling settings
	 */
	error_reporting(E_ALL & ~E_NOTICE);
	set_exception_handler("exception_handler");
	set_error_handler("error_handler", E_ALL & ~E_NOTICE);
?>
