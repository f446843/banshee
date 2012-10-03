<?php
	class admin_agenda_controller extends controller {
		public function show_agenda_overview() {
			if (($appointments = $this->model->get_appointments()) === false) {
				$this->output->add_tag("result", "Database error");
			} else {
				$this->output->open_tag("overview");
				$this->output->open_tag("appointments", array("now" => time()));
				foreach ($appointments as $appointment) {
					$appointment = $this->model->appointment_db_to_form($appointment);
					$this->output->record($appointment, "appointment");
				}
				$this->output->close_tag();
				$this->output->close_tag();
			}
		}

		public function show_appointment_form($appointment) {
			$this->output->add_javascript("ckeditor/ckeditor.js");
			$this->output->add_javascript("start_ckeditor.js");
			$this->output->add_javascript("calendar.js");
			$this->output->add_javascript("calendar-en.js");
			$this->output->add_javascript("calendar-setup.js");
			$this->output->add_javascript("admin/agenda.js");

			$appointment = $this->model->appointment_db_to_form($appointment);
			$this->output->record($appointment, "edit");

			$this->output->onload_javascript("setup_calendars('".$appointment["all_day"]."')");
		}

		public function execute() {
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Save appointment") {
					/* Save appointment
					 */
					$appointment = $this->model->appointment_form_to_db($_POST);
					if ($this->model->appointment_oke($appointment) == false) {
						$this->show_appointment_form($appointment);
					} else if (isset($_POST["id"]) == false) {
						/* Create appointment
						 */
						if ($this->model->create_appointment($appointment) == false) {
							$this->output->add_message("Error while creating appointment.");
							$this->show_appointment_form($appointment);
						} else {
							$this->user->log_action("appointment %d created", $db->last_insert_id);
							$this->show_agenda_overview();
						}
					} else {
						/* Update appointment
						 */
						if ($this->model->update_appointment($appointment) == false) {
							$this->output->add_message("Error while updateing appointment.");
							$this->show_appointment_form($appointment);
						} else {
							$this->user->log_action("appointment %d updated", $_POST["id"]);
							$this->show_agenda_overview();
						}
					}
				} else if ($_POST["submit_button"] == "Delete appointment") {
					/* Delete appointment
					 */
					if ($this->model->delete_appointment($_POST["id"]) == false) {
						$this->output->add_tag("result", "Error while deleting appointment.");
					} else {
						$this->user->log_action("appointment %d deleted", $_POST["id"]);
						$this->show_agenda_overview();
					}
				} else {
					$this->show_agenda_overview();
				}
			} else if ($this->page->pathinfo[2] == "new") {
				/* New appointment
				 */
				$appointment = array(
					"begin"    => date("Y-m-d")." 12:00",
					"end"      => date("Y-m-d")." 15:00");
				$this->show_appointment_form($appointment);
			} else if (valid_input($this->page->pathinfo[2], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)) {
				/* Edit appointment
				 */
				if (($appointment = $this->model->get_appointment($this->page->pathinfo[2])) == false) {
					$this->output->add_tag("result", "Agendapunten niet gevonden.");
				} else {
					$this->show_appointment_form($appointment);
				}
			} else {
				/* Show month
				 */
				$this->show_agenda_overview();
			}
		}
	}
?>
