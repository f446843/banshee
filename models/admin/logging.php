<?php
	class admin_logging_model extends model {
		private function arrange_data($data) {
			if ($data === false) {
				return false;
			}

			$now = time();

			$result = array();
			for ($i = LOG_DAYS - 1; $i >= 0; $i--) {
				$day = date("Y-m-d", strtotime("now - ".$i." days"));

				$count = 0;
				foreach ($data as $item) {
					if ($item["date"] == $day) {
						$count = $item["count"];
						break;
					}
				}

				$result[$day] = array(
					"date"  => $day,
					"count" => $count);
			}

			return $result;
		}

		public function get_visits($limit) {
			$query = "select date,count from log_visits order by date desc limit %d";

			$result = $this->db->execute($query, $limit);
			return $this->arrange_data($result);
		}

		public function get_page_views($limit) {
			$query = "select date,sum(%S) as %S from log_page_views group by date order by date desc limit %d";

			$result = $this->db->execute($query, "count", "count", $limit);
			return $this->arrange_data($result);
		}

		public function max_value($items, $key) {
			$max = 0;
			foreach ($items as $item) {
				if ($item[$key] > $max) {
					$max = $item[$key];
				}
			}

			return $max;
		}

		public function pad_data($data, $days) {
			if (($left = $days - count($data)) <= 0) {
				return;
			}

			for ($i = $days - $left; $i < $days; $i++) {
				$new = array(
					"date"  => date("Y-m-d", strtotime("-".$i." days")),
					"count" => 0);
				array_unshift($data, $new);
			}

			return $data;
		}

		public function get_top_pages($limit, $day = null) {
			$query = "select page,sum(count) as count from log_page_views";
			$args = array();
			if ($day !== null) {
				$query .= " where date=%s";
				array_push($args, $day);
			}
			$query .= " group by page order by count desc limit %d";
			array_push($args, $limit);

			return $this->db->execute($query, $args);
		}

		public function get_search_queries($limit, $day = null) {
			$query = "select query,sum(count) as count from log_search_queries";
			$args = array();
			if ($day !== null) {
				$query .= " where date=%s";
				array_push($args, $day);
			}
			$query .= " group by query order by count desc limit %d";
			array_push($args, $limit);

			return $this->db->execute($query, $args);
		}

		public function get_referers($day = null) {

			$query = "select hostname, sum(count) as count from log_referers";
			$args = array();
			if ($day !== null) {
				$query .= " where date=%s";
				array_push($args, $day);
			}
			$query .= " group by hostname order by count desc";

			if (($hosts = $this->db->execute($query, $args)) === false) {
				return false;
			}

			$referers = array();

			$query = "select url,sum(count) as count from log_referers where ";
			if ($day !== null) {
				$query .= " date=%s and";
			}
			$query .= " hostname=%s group by url order by count desc";
			foreach ($hosts as $host) {
				if (($result = $this->db->execute($query, $args, $host["hostname"])) === false) {
					return false;
				}
				$referers[$host["hostname"]] = $result;
			}

			return $referers;
		}

		public function delete_referers($referer) {
			$query = "delete from log_referers where hostname=%s";

			if ($this->db->query($query, $referer["hostname"]) === false) {
				return false;
			}

			if (is_true($referer["block"])) {
				if ($this->settings->log_ignore_referers == null) {
					$this->settings->log_ignore_referers = $referer["hostname"];
				} else {
					$data = explode(",", $this->settings->log_ignore_referers);
					if (in_array($referer["hostname"], $data) == false) {
						array_push($data, $referer["hostname"]);
						$this->settings->log_ignore_referers = implode(",", $data);
					}
				}
			}

			return true;
		}
	}
?>
