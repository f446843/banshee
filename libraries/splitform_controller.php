<?php
	/* libraries/splitform_controller.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	abstract class splitform_controller extends controller {
		protected $button_previous = "<< prev";
		protected $button_next = "next >>";
		protected $button_submit = "Submit";
		protected $button_back = "Back";
		protected $back = null;

		/* Main function
		 *
		 * INPUT:  array( string key => string value, ... ) form data
		 * OUTPUT: true
		 * ERROR:  false
		 */
		protected function process_form_data($data) {
			print "Splitform controller has no process_form_data() function.\n";
			return false;
		}

		/* Main function
		 *
		 * INPUT:  -
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function execute() {
			if (is_a($this->model, "splitform_model") == false) {
				print "Splitform model has not been defined.\n";
				return false;
			}

			/* Check class settings
			 */
			if ($this->model->class_settings_oke() == false) {
				return false;
			}

			/* Start
			 */
			$this->output->add_css("banshee/splitform.css");

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["splitform_current"] != $this->model->current) {
					/* Refresh button pressed
					 */
					$this->model->load_form_data();
				} else if ($_POST["submit_button"] == $this->button_previous) {
					/* Previous button pressed
					 */
					if ($this->model->current > 0) {
						$this->model->save_post_data();
						$this->model->current--;
						$this->model->load_form_data();
					} else {
						return false;
					}
				} else if (($_POST["submit_button"] == $this->button_next) || ($_POST["submit_button"] == $this->button_submit)) {
					/* Next or submit button pressed
					 */
					$this->model->save_post_data();

					if ($this->model->form_data_oke($_POST)) {
						if ($this->model->current < $this->model->max) {
							/* Subform oke
							 */
							$this->model->current++;
							$this->model->load_form_data();
						} else if ($this->process_form_data($this->model->values) == false) {
							/* Submit error
							 */
							$this->output->add_tag("result", "Error while processing form information.");
							return false;
						} else {
							/* Submit oke
							 */
							$this->output->open_tag("submit");
							foreach ($this->model->values as $key => $value) {
								$this->output->add_tag("value", $value, array("key" => $key));
							}
							$this->output->close_tag();

							unset($_SESSION["splitform"][$this->page->module]);
							return true;
						}
					}
				}
			} else {
				$this->model->load_form_data();
			}

			$this->output->open_tag("splitforms");
			$this->output->add_tag("current", $this->model->current, array("max" => $this->model->max));

			/* Show the webform
			 */
			$this->output->open_tag("splitform");
			$this->output->open_tag($this->model->forms[$this->model->current]["template"]);
			foreach ($_POST as $key => $value) {
				$this->output->add_tag($key, $value);
			}
			$this->output->close_tag();
			$this->output->close_tag();

			/* Show the button labels
			 */
			$this->output->open_tag("buttons");
			$this->output->add_tag("previous", $this->button_previous);
			$this->output->add_tag("next", $this->button_next);
			$this->output->add_tag("submit", $this->button_submit);
			if ($this->back !== null) {
				$this->output->add_tag("back", $this->button_back, array("link" => $this->back));
			}
			$this->output->close_tag();

			$this->output->close_tag();

			return true;
		}
	}
?>
