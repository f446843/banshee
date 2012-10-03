<?php
	/* libraries/splitform_model.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	abstract class splitform_model extends model {
		private $current = null;
		private $values = null;
		private $max = null;
		protected $forms = null;

		/* Constructor
		 *
		 * INPUT:  core objects
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct() {
			$arguments = func_get_args();
			call_user_func_array(array(parent, "__construct"), $arguments);

			$this->max = count($this->forms) - 1;

			if (isset($_SESSION["splitform"]) == false) {
				$_SESSION["splitform"] = array();
			}

			if (isset($_SESSION["splitform"][$this->page->module]) == false) {
				$_SESSION["splitform"][$this->page->module] = array(
					"current" => 0,
					"values"  => array());
			}

			$this->current = &$_SESSION["splitform"][$this->page->module]["current"];
			$this->values = &$_SESSION["splitform"][$this->page->module]["values"];
		}

		/* Magic method set
		 *
		 * INPUT:  string key, mixed value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __set($key, $value) {
			if ($key == "current") {
				if (($value >= 0) && ($value <= $this->max)) {
					$this->current = $value;
				}
			}
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			switch ($key) {
				case "current": return $this->current;
				case "forms": return $this->forms;
				case "values": return $this->values;
				case "max": return $this->max;
			}

			return null;
		}

		/* Reset splitform progress
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function reset_form_progress() {
			$this->current = 0;
			$this->values = array();
		}

		/* Default values for form elements
		 *
		 * INPUT:  string key, string value
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function default_value($key, $value) {
			if (isset($this->values[$key]) == false) {
				$this->values[$key] = $value;
			}
		}

		/* Save $_POST data in $_SESSION
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function save_post_data() {
			foreach ($this->forms[$this->current]["elements"] as $element) {
				$this->values[$element] = $_POST[$element];
			}
		}

		/* Restore $_SESSION data to $_POST
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function load_form_data() {
			$_POST = array();
			foreach ($this->forms[$this->current]["elements"] as $element) {
				$_POST[$element] = $this->values[$element];
			}
		}

		/* Dummy validate function
		 *
		 * INPUT:  array( string key => string value, ... ) form data
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function form_data_oke($data) {
			return true;
		}

		/* Check class settings
		 *
		 * INPUT:  -
		 * OUTPUT: boolean class validation oke
		 * ERROR:  -
		 */
		public function class_settings_oke() {
			$class_oke = true;

			if (is_array($this->forms) == false) {
				print "Forms not set in ".get_class($this)."\n";
				$class_oke = false;
			} else foreach ($this->forms as $form) {
				if (isset($form["template"]) == false) {
					print "Template in form not set in ".get_class($this)."\n";
					$class_oke = false;
				}
				if (is_array($form["elements"]) == false) {
					print "Elements in form not set in ".get_class($this)."\n";
					$class_oke = false;
				}
			}

			if ($this->max < 0) {
				print "Forms not filled in ".get_class($this)."\n";
				$class_oke = false;
			}

			return $class_oke;
		}
	}
?>
