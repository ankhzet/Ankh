<?php namespace Ankh\Feeds;

use Illuminate\Http\Request;

use Ankh\Contracts\Feeds\FeedChanel;

class FeedChanels {

	protected $chanels = [];

	public function register(FeedChanel $chanel) {
		$this->chanels[strtolower($chanel->name())] = $chanel;
	}

	public function chanels() {
		return $this->chanels;
	}

	public function resolve(Request $request) {
		$chanel = $request->route('chanel') ?: array_keys($this->chanels)[0];

		if (!isset($this->chanels[$chanel]))
			return null;

		return $this->chanels[$chanel]->of(intval($request->route('id')));
	}

}

