<?php
	class agenda_model extends model {
		public function get_appointments_from_today() {
			$query = "select *, UNIX_TIMESTAMP(begin) as begin, UNIX_TIMESTAMP(end) as end ".
					 "from agenda where (begin>=%s) order by begin";
			$today = date("Y-m-d 00:00:00");

			return $this->db->execute($query, $today);
		}

		public function get_appointments_for_month($month, $year) {
			$begin_timestamp = $this->monday_before($month, $year);
			$begin = date("Y-m-d 00:00:00", $begin_timestamp);

			$end_timestamp = $this->sunday_after($month, $year);
			$end = date("Y-m-d 23:59:59", $end_timestamp);

			$query = "select *, UNIX_TIMESTAMP(begin) as begin, UNIX_TIMESTAMP(end) as end ".
					 "from agenda ".
					 "where (begin>=%s and begin<%s) or (end>=%s and end<%s) or (begin<%s and end>=%s) ".
					 "order by begin";

			return $this->db->execute($query, $begin, $end, $begin, $end, $begin, $end);
		}

		public function get_appointment($appointment_id) {
			$query = "select *, UNIX_TIMESTAMP(begin) as begin, UNIX_TIMESTAMP(end) as end ".
					 "from agenda where id=%d";

			if (($result = $this->db->execute($query, $appointment_id)) == false) {
				return false;
			}

			return $result[0];
		}

		public function monday_before($month, $year) {
			$timestamp = strtotime($year."-".$month."-01 00:00:00");
			$dow = date("N", $timestamp) - 1;
			$timestamp -= $dow * DAY;

			return $timestamp;
		}

		public function sunday_after($month, $year) {
			$timestamp = strtotime($year."-".$month."-01 00:00:00 +1 month") - DAY;
			if (($dow = date("N", $timestamp)) < 7) {
				$timestamp += (7 - $dow) * DAY;
			}

			return $timestamp;
		}
	}
?>
