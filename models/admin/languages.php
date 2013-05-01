<?php
	class admin_languages_model extends tablemanager_model {
		protected $table = "languages";
		protected $order = "page";
		protected $elements = array(
			"page" => array(
				"label"    => "Page",
				"type"     => "enum",
				"overview" => true,
				"required" => true,
				"options"  => array()),
			"name" => array(
				"label"    => "Name",
				"type"     => "varchar",
				"overview" => true,
				"required" => true));

		public function __construct() {
			$arguments = func_get_args();
			call_user_func_array(array(parent, "__construct"), $arguments);

			if ($this->language === null) {
				return;
			}

			/* Add supported languages
			 */
			foreach ($this->language->supported as $lang => $label) {
				$this->elements[$lang] = array(
					"label"    => $label,
					"type"     => "text",
					"overview" => false,
					"required" => true);
			}

			/* Set page options
			 */
			$modules = page_to_module(array_merge(public_pages(), private_pages()));
			sort($modules);
			array_unshift($modules, "*");
			$modules = array_combine($modules, $modules);
			$this->elements["page"]["options"] = $modules;
		}

		public function save_oke($item) {
			$result = parent::save_oke($item);

			if (valid_input($item["name"], VALIDATE_LETTERS."_", VALIDATE_NONEMPTY) == false) {
				$this->output->add_message("Invalid name");
				$result = false;
			}

			return $result;
		}

		public function update_item($item) {
			$this->output->remove_from_cache("language");

			return parent::update_item($item);
		}

		public function delete_item($item_id) {
			$this->output->remove_from_cache("language");

			return parent::delete_item($item_id);
		}
	}
?>
