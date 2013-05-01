<?php
	/* libraries/openstreetmap.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class OpenStreetMap extends HTTP {
		private $cache = null;
		private $hostname = "pafciu17.dev.openstreetmap.org";
		private $types = array("mapnik", "cycle", "osma");
		private $colors = array("red", "blue", "green");
		private $formats = array("png", "jpg", "gif");
		private $type = null;
		private $markers = array();
		private $routes = array();
		private $center = "0,0";
		private $zoom = 1;

		/* Constructor
		 *
		 * INPUT:  object database
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($db) {
			$this->cache = new cache($db, "openstreetmap");
			parent::__construct($this->hostname);
		}

		/* Magic method set
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __set($key, $value) {
			switch ($key) {
				case "type": if (in_array($value, $this->types)) $this->type = $value; break;
				case "format": if (in_array($value, $this->formats)) $this->format = $value; break;
				case "zoom": $this->zoom = $value; break;
			}
		}

		private function location_string_to_array($location) {
			$parts = explode(",", $location);
			foreach ($parts as $i => $part) {
				$parts[$i] = strtolower(trim($part));
			}

			return $parts;
		}

		/* Get coordinates for locaton
		 *
		 * INPUT:  string location
		 * OUTPUT: array coordinates
		 * ERROR:  false;
		 */
		private function location_to_coord($location) {
			static $nominatim = null;
			$hit_types = array("unclassified", "tertiary", "secondary", "residential", "city");
			$sel_match = 0;
			$sel_type = -1;

			$url = "/search?q=".urlencode($location)."&format=json&addressdetails=1&osm_type=N";
			$key = md5($url);

			if (($coords = $this->cache->$key) === null) {
				if ($nominatim === null) {
					$nominatim = new HTTP("nominatim.openstreetmap.org");
				}

				$result = $nominatim->GET($url);
				if ($result["status"] != 200) {
					return false;
				}

				if (($data = json_decode($result["body"], true)) === NULL) {
					return false;
				}

				$loc_parts = $this->location_string_to_array($location);

				$coords = false;
				foreach ($data as $record) {
					$rec_parts = $this->location_string_to_array($record["display_name"]);

					$match = count(array_intersect($loc_parts, $rec_parts));
					if (($type = array_search($record["type"], $hit_types)) === false) {
						$type = 0;
					}

					if (($match > $sel_match) && ($type > $sel_type)) {
						$coords = array(
							"lat" => $record["lat"],
							"lon" => $record["lon"]);

						$sel_match = $match;
						$sel_type = $type;
					}
				}

				if (($coords == false) && (count($data) > 0)) {
					$coords = array(
						"lat" => $data[0]["lat"],
						"lon" => $data[0]["lon"]);
				}

				if ($coords != false) {
					$this->cache->$key = $coords;
				}
			}

			return $coords;
		}

		/* Set map center
		 *
		 * INPUT:  (float latitude, float longitude | string position)
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function set_center($latitude, $longitude = null) {
			if ($longitude === null) {
				if (($result = $this->location_to_coord($latitude)) == false) {
					return false;
				}
				$latitude  = $result["lat"];
				$longitude = $result["lon"];
			}

			$this->center = $longitude.",".$latitude;

			return true;
		}

		/* Add marker to map
		 *
		 * INPUT:  (float latitude, float longitude | string position)
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function add_marker($label, $color, $latitude, $longitude = null) {
			if ($longitude === null) {
				if (($result = $this->location_to_coord($latitude)) == false) {
					return false;
				}
				$latitude  = $result["lat"];
				$longitude = $result["lon"];
			}

			$label = strtoupper(substr($label, 0, 1));
			$ol = ord($label);
			if (($ol < ord("0")) || (($ol > ord("9")) && ($ol < ord("A"))) || ($ol > ord("Z"))) {
				$label = "X";
			}

			if (in_array($color, $this->colors) == false) {
				$color = $this->colors[0];
			}

			array_push($this->markers, $longitude.",".$latitude.",pointImagePattern:".$color.$label);

			return true;
		}

		/* Add path point
		 *
		 * INPUT:  string point, string point[, string point, ...]
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function add_route() {
			if (($args = func_num_args()) < 2) {
				return false;
			}

			$points = array();
			for ($i = 0; $i < $args; $i++) {
				if (($point = $this->location_to_coord(func_get_arg($i))) != false) {
					array_push($points, $point);
				}
			}

			if (($args = count($points)) < 2) {
				return false;
			}

			$route = array();
			foreach ($points as $point) {
				array_push($route, $point["lon"].",".$point["lat"]);
			}

			array_push($this->routes, implode(",", $route).",thickness:3");

			return true;
		}

		/* Generate static map path
		 *
		 * INPUT:  int image width[, int image height]
		 * OUTPUT: string url
		 * ERROR:  -
		 */
		private function generate_path($size_x, $size_y = null) {
			if ($size_y === null) {
				$size_y = $size_x;
			}

			$parameters = array(
				"module=map",
				"width=".$size_x,
				"height=".$size_y);

			$keys = array("type", "center", "zoom");
			foreach ($keys as $key) {
				if ($this->$key !== null) {
					array_push($parameters, $key."=".$this->$key);
				}
			}

			/* Image format
			 */
			if ($this->format != null) {
				array_push($parameters, "imgType=".$this->format);
			}

			/* Add markers
			 */
			if (count($this->markers) > 0) {
				array_push($parameters, "points=".implode(";", $this->markers));
			}

			/* Add routes
			 */
			if (count($this->routes) > 0) {
				array_push($parameters, "paths=".implode(";", $this->routes));
			}

			return "/?".implode("&", $parameters);
		}

		/* Generate static map URL
		 *
		 * INPUT:  int image width[, int image height]
		 * OUTPUT: string url
		 * ERROR:  -
		 */
		public function generate_url($size_x, $size_y = null) {
			return "http://".$this->hostname.$this->generate_path($size_x, $size_y);
		}

		/* Send static map to client
		 *
		 * INPUT:  int image width[, int image height]
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function show_static_map($size_x, $size_y = null) {
			$path = $this->generate_path($size_x, $size_y);

			$key = md5($path);
			if (($data = $this->cache->$key) === null) {
				/* Fetch from website
				 */
				$result = $this->GET($path);
				if ($result["status"] != 200) {
					return false;
				}

				$content_type = $result["headers"]["Content-Type"];
				$image_data = $result["body"];

				$this->cache->$key = array($content_type, base64_encode($image_data));
			} else {
				list($content_type, $image_data) = $data;
				$image_data = base64_decode($image_data);
			}

			header("Content-Type: ".$content_type);
			print $image_data;

			return true;
		}
	}
?>
