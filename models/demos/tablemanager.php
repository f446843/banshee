<?php
	class demos_tablemanager_model extends tablemanager_model {
		protected $table = "dummy";
		protected $elements = array(
			"number" => array(
				"label"    => "Number",
				"type"     => "integer",
				"overview" => true,
				"required" => true),
			"line" => array(
				"label"    => "Line",
				"type"     => "varchar",
				"overview" => true,
				"unique"   => true,
				"required" => true),
			"text" => array(
				"label"    => "Text",
				"type"     => "text",
				"required" => true),
			"boolean" => array(
				"label"    => "Boolean",
				"type"     => "boolean",
				"overview" => true),
			"timestamp" => array(
				"label"    => "Timestamp",
				"type"     => "datetime",
				"overview" => true),
			"enum" => array(
				"label"    => "Enum",
				"type"     => "enum",
				"options"   => array(
					"value1" => "Value one",
					"value2" => "Value two",
					"value3" => "Value three")),
			"user_id" => array(
				"label"    => "User",
				"type"     => "foreignkey",
				"table"    => "users",
				"column"   => "fullname",
				"overview" => true,
				"required" => false));
	}
?>
