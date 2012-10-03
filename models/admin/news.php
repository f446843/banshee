<?php
	class admin_news_model extends tablemanager_model {
		protected $table = "news";
		protected $order = "timestamp";
		protected $elements = array(
			"title" => array(
				"label"    => "Title",
				"type"     => "varchar",
				"overview" => true,
				"required" => true),
			"content" => array(
				"label"    => "Content",
				"type"     => "ckeditor",
				"required" => true),
			"timestamp" => array(
				"label"    => "Timestamp",
				"type"     => "datetime",
				"overview" => true,
				"readonly" => true));

		public function create_item($item) {
			$item["timestamp"] = date("Y-m-d H:i:s");
			parent::create_item($item);
		}
	}
?>
