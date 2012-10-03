<?php
	class demos_pagination_controller extends controller {
		public function execute() {
			$this->output->title = "Pagination demo";

			$list = array();
			for ($i = 0; $i < 200; $i++) {
				array_push($list, "List item ".($i + 1));
			}

			$paging = new pagination($this->output, "demo", 15, count($list));
			$items = array_slice($list, $paging->offset, $paging->size);

			$this->output->open_tag("items");
			foreach ($items as $item) {
				$this->output->add_tag("item", $item);
			}
			$this->output->close_tag();

			$paging->show_browse_links();
		}
	}
?>
