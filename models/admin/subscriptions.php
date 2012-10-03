<?php
	class admin_subscriptions_model extends tablemanager_model {
		protected $table = "subscriptions";
		protected $order = "email";
		protected $elements = array(
			"email" => array(
				"label"    => "E-mail address",
				"type"     => "varchar",
				"overview" => true,
				"required" => true));
	}
?>
