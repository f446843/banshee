<?php
	class demos_openstreetmap_controller extends controller {
		private function show_static() {
			$openstreetmap = new OpenStreetMap($this->db);

			$openstreetmap->add_route("Amsterdam, NL", "Utrecht, NL", "Breda, NL", "Brussel, BE", "Lille, FR", "Paris, FR");

			$openstreetmap->add_marker("D", "red", "Den Haag, NL");
			$openstreetmap->add_marker("L", "blue", "London, EN");
			$openstreetmap->add_marker("B", "green", "Bonn, DE");

			$openstreetmap->set_center("Brussel, BE");

			$openstreetmap->zoom = 6;
			$openstreetmap->type = "osmarender";

			$openstreetmap->show_static_map(640, 350);

			$this->output->disabled = true;
		}

		public function execute() {
			if ($this->page->pathinfo[2] == "image") {
				$this->show_static();
				return;
			}
		}
	}
?>
