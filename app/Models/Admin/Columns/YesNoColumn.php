<?php
	namespace Ankh\Admin\Columns;

	use SleepingOwl\Admin\Columns\Column\BaseColumn;

	class YesNoColumn extends BaseColumn {

		public function render($instance, $totalCount) {
			$content = ($instance->{$this->name}) ? 'yes' : '';
			return parent::render($instance, $totalCount, $content);
		}

	}
