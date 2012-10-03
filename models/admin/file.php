<?php
	class admin_file_model extends model {
		public function filename_oke($file) {	
			if (trim($file) == "") {
				return false;
			}

			return valid_input($file, VALIDATE_NUMBERS.VALIDATE_LETTERS."/-_. ");
		}

		public function directory_listing($directory) {
			if (($dp = opendir($directory)) == false) {
				return false;
			}

			$files = $dirs = array();
			while (($file = readdir($dp)) !== false) {
				if ($file[0] == ".") {
					continue;
				}
				if (is_dir($directory."/".$file)) {
					array_push($dirs, $file);
				} else {
					array_push($files, $file);
				}
			}

			closedir($dp);

			sort($files);
			sort($dirs);

			return array(
				"dirs"  => $dirs,
				"files" => $files);
		}

		public function get_file_size($file) {
			if (($size = filesize($file)) === false) {
				return false;
			}

			if ($size > 1048576) {
				return sprintf("%.2f MB", $size / 1048576);
			} else if ($size > 1024) {
				return sprintf("%.2f kB", $size / 1024);
			}

			return $size." byte";
		}

		public function upload_oke($file, $directory) {
			global $allowed_uploads;

			if ($file["error"] !== 0) {
				$this->output->add_message("Error while uploading file.");
				return false;
			}

			if ($this->filename_oke($directory."/".$file["name"]) == false) {
				$this->output->add_message("Invalid filename.");
				return false;
			}
			if (($ext = strrchr($file["name"], ".")) === false) {
				$this->output->add_message("File has no extension.");
				return false;
			}
			if (in_array(substr($ext, 1), $allowed_uploads) == false) {
				$this->output->add_message("Invalid file extension.");
				return false;
			}
			if (file_exists($directory."/".$file["name"])) {
				$this->output->add_message("File already exists.");
				return false;
			}

			return true;
		}

		public function import_uploaded_file($file, $directory) {
			return move_uploaded_file($file["tmp_name"], $directory."/".$file["name"]);
		}

		public function delete_file($file, $directory) {
			if ($this->filename_oke($file) == false) {	
				return false;
			}
			$file = $directory."/".$file;

			return is_dir($file) ? @rmdir($file) : @unlink($file);
		}

		public function directory_empty($subdir, $directory) {
			if (($dp = opendir($directory."/".$subdir)) == false) {
				return false;
			}

			$result = true;
			$allowed = array(".", "..");
			while (($file = readdir($dp)) !== false) {
				if (in_array($file, $allowed) == false) {
					$result = false;
					break;
				}
			}
			closedir($dp);

			return $result;
		}

		public function directory_oke($subdir, $directory) {
			$result = true;

			if ($this->filename_oke($subdir) == false) {
				$this->output->add_message("Invalid directory name.");
				$result = false;
			} else if (file_exists($directory."/".$subdir)) {
				$this->output->add_message("Directory already exists.");
				$result = false;
			}

			return $result;
		}

		public function create_directory($subdir, $directory) {
			return @mkdir($directory."/".$subdir);
		}
	}
?>
