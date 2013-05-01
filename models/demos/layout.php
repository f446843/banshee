<?php
	class demos_layout_model extends model {
		public function get_layouts() {
			if (($fp = fopen("../views/banshee/main.xslt", "r")) == false) {
				return false;
			}

			$result = array();
			while (($line = fgets($fp)) !== false) {
				if (strpos($line, "apply-templates") !== false) {
					list(, $layout) = explode('"', $line);
					list(, $layout) = explode("_", $layout, 2);
					array_push($result, $layout);
				}
			}

			fclose($fp);

			return $result;
		}
	}
?>
