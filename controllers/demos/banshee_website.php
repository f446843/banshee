<?php
	class demos_banshee_website_controller extends controller {
		public function execute() {
			$website = new banshee_website("demo.banshee-php.org");

			if ($website->login("admin", "banshee") == false) {
				$this->output->add_tag("message", "Login failed.");
				return;
			}

			$this->output->add_tag("message", "Login successful.");
			
			if (($result = $website->GET("/admin/user/1")) == false) {
				$this->output->add_tag("message", "Error fetching webpage.");
				return;
			}

			$this->output->add_tag("message", "Remote page fetched.");

			if (($name = $website->array_path($result, "/output/content/edit/user/fullname")) == false) {
				$this->output->add_tag("message", "Error fetching information about user 'admin'.");
				return;
			}

			$this->output->add_tag("message", "Full name of user 'admin' is '".$name."'.");
		}
	}
?>
