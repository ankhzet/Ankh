<?php
	namespace Ankh;

	use SleepingOwl\Admin\Columns\Column\BaseColumn;

	class SizeColumn extends BaseColumn {

		public function render($instance, $totalCount) {
			$content = human_filesize($instance->{$this->name});
			return parent::render($instance, $totalCount, $content);
		}

	}

	function human_filesize($bytes, $dec = 2) {
		$bytes = intval($bytes);
		$size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$factor = floor((strlen("$bytes") - 1) / 3);

		return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}
