<?php namespace Ankh\Contracts\Downloadable;

interface Zipable {

	function path();
	function datetime();
	function data();
	function comment();

}

