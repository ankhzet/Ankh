<?php namespace Ankh\Contracts;

interface Orderable {

	public function orderWith(OrderingDescriptor $descriptor);

}
