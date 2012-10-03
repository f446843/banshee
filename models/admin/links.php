<?php
	class admin_links_model extends tablemanager_model {
		protected $table = "links";
		protected $order = "text";
		protected $elements = array(
			"text" => array(
				"label"    => "Text",
				"type"     => "varchar",
				"overview" => true,
				"required" => true),
			"link" => array(
				"label"    => "Link",
				"type"     => "varchar",
				"overview" => true,
				"required" => true));
	}
?>
