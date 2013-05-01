<?php
	/* libraries/image.php
	 *
	 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
	 * This file is part of the Banshee PHP framework
	 * http://www.banshee-php.org/
	 */

	abstract class image {
		public $resource = null;
		protected $load_image = null;
		protected $save_image = null;
		protected $mime_type = null;
		protected $filename = null;
		private $width = null;
		private $height = null;

		/* Constructor
		 *
		 * INPUT:  string filename
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __construct($filename = null) {
			if ($filename !== null) {
				$this->load($filename);
			}
		}

		/* Destructor
		 *
		 * INPUT:  -
		 * OUTPUT: -
		 * ERROR:  -
		 */
		public function __destruct() {
			if ($this->resource !== null) {
				imagedestroy($this->resource);
			}
		}

		/* Magic method get
		 *
		 * INPUT:  string key
		 * OUTPUT: mixed value
		 * ERROR:  null
		 */
		public function __get($key) {
			if ($this->resource === null) {
				return null;
			}

			switch ($key) {
				case "loaded": return $this->resource !== null;
				case "width": return $this->width;
				case "height": return $this->height;
			}

			return null;
		}

		/* Load image
		 *
		 * INPUT:  string filename
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function load($filename) {
			if (($resource = call_user_func($this->load_image, $filename)) === false) {
				return false;
			}

			$this->resource = $resource;
			$this->filename = $filename;
			$this->width = imagesx($resource);
			$this->height = imagesy($resource);

			return true;
		}

		/* Save image
		 *
		 * INPUT:  string filename
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function save($filename = null) {
			if ($this->resource === null) {
				return false;
			}

			if ($filename === null) {
				$filename = $this->filename;
			}

			return call_user_func($this->save_image, $this->resource, $filename);
		}

		/* Image from string
		 *
		 * INPUT:  string image
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function from_string($image) {
			if (($resource = imagecreatefromstring($image)) === false) {
				return false;
			}

			$this->resource = $resource;
			$this->update_size();

			return true;
		}

		/* Update image size information
		 *
		 * INPUT:  -
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function update_size() {
			if ($this->resource === null) {
				return false;
			}

			$this->width = imagesx($this->resource);
			$this->height = imagesy($this->resource);

			return true;
		}

		/* Resize image
		 *
		 * INPUT:  int new height[, int max new width]
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function resize($height, $max_width = null) {
			if ($this->resource === null) {
				return false;
			}

			$width = ($this->width * $height) / $this->height;
			$width = round($width, 0);

			if (($max_width !== null) && ($width > $max_width)) {
				$width = $max_width;
				$height = ($this->height * $width) / $this->width;
				$height = round($height, 0);
			}

			if (($resource = imagecreatetruecolor($width, $height)) == false) {
				return false;
			}
			if (imagecopyresampled($resource, $this->resource, 0, 0, 0, 0, $width, $height, $this->width, $this->height) == false) {
				return false;
			}

			imagedestroy($this->resource);
			$this->resource = $resource;
			$this->update_size();

			return true;
		}

		/* Send image to client
		 *
		 * INPUT:  object output
		 * OUTPUT: true
		 * ERROR:  false
		 */
		public function to_output($output) {
			if (headers_sent()) {
				return false;
			}

			$output->disable();

			header("Content-Type: ".$this->mime_type);
			return call_user_func($this->save_image, $this->resource);
		}
	}

	/* JPEG image
	 */
	class jpeg_image extends image {
		public function __construct($filename = null) {
			$this->load_image = "imagecreatefromjpeg";
			$this->save_image = "imagejpeg";
			$this->mime_type  = "image/jpeg";

			parent::__construct($filename);
		}
	}

	/* PNG image
	 */
	class png_image extends image {
		public function __construct($filename = null) {
			$this->load_image = "imagecreatefrompng";
			$this->save_image = "imagepng";
			$this->mime_type  = "image/png";

			parent::__construct($filename);
		}
	}

	/* GIF image
	 */
	class gif_image extends image {
		public function __construct($filename = null) {
			$this->load_image = "imagecreatefromgif";
			$this->save_image = "imagegif";
			$this->mime_type  = "image/gif";

			parent::__construct($filename);
		}
	}
?>
