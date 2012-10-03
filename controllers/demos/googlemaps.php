<?php
	class demos_googlemaps_controller extends controller {
		private $origin = "Amsterdam, NL";
		private $destination = "Paris, FR";

		private function show_static_map() {
			$google_map = new GoogleMaps($this->db);

			$google_map->add_route($this->origin, $this->destination);

			$google_map->add_marker("D", "red", "Den Haag, NL");
			$google_map->add_marker("L", "yellow", "London, EN");
			$google_map->add_marker("B", "green", "Bonn, DE");

			$google_map->set_visibility("Stuttgart, DE");

			$google_map->show_static_map(640, 350);

			$this->output->disabled = true;
		}

		public function execute() {
			if ($this->page->pathinfo[2] == "image") {
				$this->show_static_map();
				return;
			}

			$google_map = new GoogleMaps($this->db);

			$google_map->add_route($this->origin, $this->destination);
			$steps = $google_map->route_description;
			$distance = $google_map->route_distance;
			$duration = $google_map->route_duration;

			$hours = $duration / 3600;
			$minutes = ($duration % 3600) / 60;

			$this->output->open_tag("route");

			$this->output->add_tag("origin", $this->origin);
			$this->output->add_tag("destination", $this->destination);
			$this->output->add_tag("distance", sprintf("%2.1f km", $distance / 1000));
			$this->output->add_tag("duration", sprintf("%d:%2d", $hours, $minutes));

			foreach ($steps as $step) {
				$this->output->add_tag("step", $step["description"], array(
					"distance" => $step["distance"],
					"duration" => $step["duration"]));
			}

			$this->output->close_tag();
		}
	}
?>
