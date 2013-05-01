<?php
	/* libraries/googlemaps.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	class GoogleMaps extends HTTP {
		private $cache = null;
		private $hostname = "maps.google.com";
		private $map_types = array("roadmap", "satellite", "terrain", "hybrid");
		private $formats = array("png", "png32", "gif", "jpg", "jpg-baseline");
		private $map_type = null;
		private $format = null;
		private $markers = array();
		private $path_weight = 5;
		private $path_color = "blue";
		private $path_points = array();
		private $center = null;
		private $zoom = null;
		private $visible = null;
		private $route_description = array();
		private $route_duration = 0;
		private $route_distance = 0;

		/* Constructor
		 *
		 * INPUT:  object database
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($db) {
			$this->cache = new cache($db, "googlemaps");
			parent::__construct($this->hostname);
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			switch ($key) {
				case "route_description": return $this->route_description;
				case "route_distance": return $this->route_distance;
				case "route_duration": return $this->route_duration;
			}

			return null;
		}

		/* Magic method set
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __set($key, $value) {
			switch ($key) {
				case "map_type": if (in_array($value, $this->map_types)) $this->map_type = $value; break;
				case "format": if (in_array($value, $this->formats)) $this->format = $value; break;
				case "route_weight": $this->path_weight = $value; break;
				case "route_color": $this->path_color = $value; break;
				case "center": $this->center = $value; break;
				case "zoom": $this->zoom = $value; break;
			}
		}

		/* Set map visiblity
		 *
		 * INPUT:  string position[, ...]
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function set_visibility() {
			if (func_num_args() == 0) {
				return false;
			}
			$locations = func_get_args();

			$visible = array();
			foreach ($locations as $location) {
				array_push($visible, urlencode($location));
			}

			$this->visible = implode("|", $visible);

			return true;
		}

		/* Add marker to map
		 *
		 * INPUT:  string color, char label, (float latitude, float longitude | string position)
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function add_marker($label, $color, $latitude, $longitude = null) {
			$label = strtoupper(substr($label, 0, 1));
			$position = $longitude === null ? urlencode($latitude) : sprintf("%F,%F", $latitude, $longitude);
			$marker = sprintf("markers=color:%s|label:%s|%s", $color, $label, $position);

			array_push($this->markers, $marker);
		}

		/* Add path point
		 *
		 * INPUT:  string start point, string end point
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function add_route($origin, $destination) {
			$parameters = array(
				"origin=".urlencode($origin),
				"destination=".urlencode($destination),
				"sensor=false");

			$url = "/maps/api/directions/json?".implode("&", $parameters);

			$key = md5($url);

			if (($route_info = $this->cache->$key) === null) {
				/* Fetch from website
				 */
				$result = $this->GET($url);
				if ($result["status"] != 200) {
					return false;
				}

				$route_info = $result["body"];
				$this->cache->$key = $route_info;
			}

			$data = json_decode($route_info, true);

			$steps = &$data["routes"][0]["legs"][0]["steps"];
			if (count($steps) <= 1) {
				return false;
			}

			/* Add route points
			 */
			array_push($this->path_points, $steps[0]["start_location"]["lat"].",".$steps[0]["start_location"]["lng"]);
			foreach ($steps as $step) {
				array_push($this->path_points, $step["end_location"]["lat"].",".$step["end_location"]["lng"]);

				array_push($this->route_description, array(
					"description" => $step["html_instructions"],
					"distance"    => $step["distance"]["text"],
					"duration"    => $step["duration"]["text"]));
				$this->route_distance += $step["distance"]["value"];
				$this->route_duration += $step["duration"]["value"];
			}

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
				"size=".$size_x."x".$size_y,
				"sensor=false");

			$keys = array("maptype", "format", "center", "zoom", "visible");
			foreach ($keys as $key) {
				if ($this->$key !== null) {
					array_push($parameters, $key."=".$this->$key);
				}
			}

			/* Add markers
			 */
			$parameters = array_merge($parameters, $this->markers);

			/* Add path
			 */
			if (count($this->path_points) > 1) {
				$points = implode("|", $this->path_points);
				$path = sprintf("path=color:%s|weight:%s|%s", $this->path_color, $this->path_weight, $points);
				array_push($parameters, $path);
			}

			return "/maps/api/staticmap?".implode("&", $parameters);
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
