<?php
	class admin_agenda_model extends model {
		public function get_appointments() {
			$query = "select * from agenda";

			return $this->db->execute($query);
		}

		public function get_appointment($appointment_id) {
			$query = "select * from agenda where id=%d";

			if (($result = $this->db->execute($query, $appointment_id)) == false) {
				return false;
			}

			return $result[0];
		}

		public function appointment_db_to_form($appointment) {
			global $months_of_year;

			list($date, $begin_time) = explode(" ", $appointment["begin"], 2);
			list($year, $month, $day) = explode("-", $date);
			$appointment["begin_date"] = $date;
			$appointment["begin_show"] = (int)$day." ".$months_of_year[$month - 1]." ".$year;
			list($hour, $minute, $minute) = explode(":", $begin_time);
			$appointment["begin_time"] = $hour.":".$minute;
			$appointment["begin"] = strtotime($appointment["begin"]);

			list($date, $end_time) = explode(" ", $appointment["end"], 2);
			list($year, $month, $day) = explode("-", $date);
			$appointment["end_date"] = $date;
			$appointment["end_show"] = (int)$day." ".$months_of_year[$month - 1]." ".$year;
			list($hour, $minute, $minute) = explode(":", $end_time);
			$appointment["end_time"] = $hour.":".$minute;
			unset($appointment["end"]);

			$appointment["all_day"] = show_boolean(($begin_time == "00:00:00") && ($end_time == "23:59:59"));

			return $appointment;
		}

		public function appointment_form_to_db($appointment) {
			if (is_true($appointment["all_day"])) {
				$appointment["begin_time"] = "00:00:00";
				$appointment["end_time"] = "23:59:59";
			}

			$appointment["begin"] = $appointment["begin_date"]." ".$appointment["begin_time"];
			$appointment["end"] = $appointment["end_date"]." ".$appointment["end_time"];

			return $appointment;
		}

		public function appointment_oke($appointment) {
			$result = true;

			if (valid_timestamp($appointment["begin"]) == false) {
				$this->output->add_message("Invalid start time.");
				$result = false;
			}
			if (valid_timestamp($appointment["end"]) == false) {
				$this->output->add_message("Invalid end time.");
				$result = false;
			}

			if ($result) {
				if (strtotime($appointment["begin"]) >= strtotime($appointment["end"])) {
					$this->output->add_message("Begin time must lie before end time.");
					$result = false;
				}
			}

			if (trim($appointment["title"]) == "") {
				$this->output->add_message("Empty short description not allowed.");
				$result = false;
			}

			return $result;
		}

		public function create_appointment($appointment) {
			$keys = array("id", "begin", "end", "title", "content");
			$appointment["id"] = null;

			return $this->db->insert("agenda", $appointment, $keys) !== false;
		}

		public function update_appointment($appointment) {
			$keys = array("begin", "end", "title", "content");

			return $this->db->update("agenda", $appointment["id"], $appointment, $keys) !== false;
		}

		public function delete_appointment($appointment_id) {
			return $this->db->delete("agenda", $appointment_id);
		}
	}
?>
