<?php
	class demos_validation_controller extends controller {
		private $pattern = array(
			"string" => array(
				"type"     => "string",
				"required" => true,
				"charset"  => VALIDATE_CAPITALS,
				"pattern"  => "^A.*",
				"minlen"   => 10,
				"maxlen"   => 15),
			"number" => array(
				"type"     => "integer",
				"required" => true),
			"enum" => array(
				"type"     => "enum",
				"required" => false,
				"values"   => array("one", "two", "three")));

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$validator = new validator($this->output);

				if ($validator->execute($this->pattern)) {
					$this->output->add_system_message("Data validation oke.");
				}
			}

			$this->output->record($_POST);
		}
	}
?>
