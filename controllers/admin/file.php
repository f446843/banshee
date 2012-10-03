<?php
	class admin_file_controller extends controller {
		public function execute() {
			$base_dir = UPLOAD_DIRECTORY;
			if (($sub_dir = implode("/", $this->page->parameters)) != "") {
				$sub_dir = "/".$sub_dir;
			}
			$directory = $base_dir.$sub_dir;

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if ($_POST["submit_button"] == "Create directory") {
					/* Create directory
					 */
					if ($this->model->directory_oke($_POST["create"], $directory) == false) {
						$this->output->add_tag("create", $_POST["create"]);
					} else if ($this->model->create_directory($_POST["create"], $directory) == false) {
						$this->output->add_tag("create", $_POST["create"]);
						$this->output->add_message("Error creating directory.");
					}
				} else if ($_POST["submit_button"] == "Upload file") {
					/* Upload file
					 */
					if ($this->model->upload_oke($_FILES["file"], $directory)) {
						if ($this->model->import_uploaded_file($_FILES["file"], $directory) == false) {
							$this->output->add_message("Error while importing file.");
						} else {
							$this->user->log_action("file '%s' uploaded", $_FILES["file"]["name"]);
						}
					}
				} else if ($_POST["submit_button"] == "delete") {
					/* Delete file
					 */
					if ($this->model->delete_file($_POST["filename"], $directory) == false) {
						$this->output->add_message("Error while deleting file.");
					} else {
						$this->user->log_action("file '%s' deleted", $_POST["filename"]);
					}
				}
			}

			if (($files = $this->model->directory_listing($directory)) === false) {
				$this->output->add_tag("result", "Error reading directory");
			} else {
				$this->output->open_tag("files", array("dir" => $sub_dir));

				/* One directory up
				 */
				$back = $this->page->parameters;
				if (count($back) > 0) {
					array_pop($back);
					if (($back = implode("/", $back)) != "") {
						$back = "/".$back;
					}
					$this->output->add_tag("back", "/".$this->page->module.$back);
				}

				/* Directories
				 */
				foreach ($files["dirs"] as $filename) {
					$file = array(
						"name"   => $filename,
						"link"   => "/".$this->page->module.$sub_dir."/".$filename,
						"size"   => $this->model->get_file_size($directory."/".$filename),
						"delete" => show_boolean($this->model->directory_empty($filename, $directory)));
					$this->output->record($file, "dir");
				}

				/* Files
				 */
				foreach ($files["files"] as $filename) {
					$file = array(
						"name"   => $filename,
						"link"   => "/".$directory."/".urlencode($filename),
						"size"   => $this->model->get_file_size($directory."/".$filename),
						"delete" => "yes");
					$this->output->record($file, "file");
				}

				$this->output->close_tag();
			}
		}
	}
?>
