<?php
	require("../libraries/captcha.php");

	class captcha_controller extends controller {
		public function execute() {
			$captcha = new captcha;
			if ($captcha->created == false) {
				exit;
			}

			$captcha->to_output();

			$this->output->disabled = true;
		}
	}
?>
