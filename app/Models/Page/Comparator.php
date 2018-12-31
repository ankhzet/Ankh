<?php namespace Ankh\Page;

use Ankh\Contracts\Synk\Fetch;
use Ankh\Version;
use Ankh\PageResolver;
use PageUtils;

class Compare {

	var $comparable1;
	var $comparable2;

	var $proclaimedSize;

	function __construct($comparable1, $comparable2, $proclaimedSize = null) {
		$this->comparable1 = $comparable1;
		$this->comparable2 = $comparable2;
		$this->proclaimedSize = $proclaimedSize;
	}

	public function equals() {
		return (
			(strlen($this->comparable1) === strlen($this->comparable2)) &&
			($this->comparable1 === $this->comparable2)
		);
	}

	public function proclaimedSize() {
		return $this->proclaimedSize ?: strlen($this->comparable1);
	}

}

class EqualCompare extends Compare {

	function __construct($comparable, $proclaimedSize = null) {
		parent::__construct($comparable, $comparable, $proclaimedSize);
	}

}

class Comparator {

	var $fetchedEncoding = 'cp1251';

	public function compareLast(Version $version) {
		$resolver = $version->resolver();
		$last = $resolver->last();

		$result = $this->compare($last, $resolver);

		if ($result === false) {
			return false;
		}

		if (!$result->equals()) {
			if (!PageUtils::putContents($last, $result->comparable2, $this->fetchedEncoding)) {
				error_handler(E_USER_ERROR, "Failed to save \"$last\"", basename(__FILE__), 28);
				return false;
			}
		}

		return $result;
	}

	public function compare(PageResolver $r1, PageResolver $r2) {
		$contents2 = PageUtils::contents($r2, $this->fetchedEncoding);

		if ($contents2 === null) {
			$fetch = app(Fetch::class);

			//todo: move this to Fetch constructor & make it configurable
			if (method_exists($fetch, 'cached'))
				$fetch = $fetch->cached(false);

			$contents2 = $fetch->pull($r2->page->absoluteLink());

			if (!$fetch->isOk())
				if ($fetch->code() == 404) {
					$r2->page->delete();
					$contents1 = PageUtils::contents($r1, $this->fetchedEncoding);

					return new EqualCompare($contents1, $this->size($contents1));
				} else
					throw new CompareFetchError($r2->page, $fetch->code());

			$size = $this->size($contents2);

			$contents2 = $this->cleanup($contents2);

			if (!PageUtils::putContents($r2, $contents2, $this->fetchedEncoding)) {
				error_handler(E_USER_ERROR, "Failed to save \"$r2\"", basename(__FILE__), 28);
				return false;
			}

			$contents2 = PageUtils::contents($r2, $this->fetchedEncoding);
		} else {
			$size = null;
		}

		$contents1 = PageUtils::contents($r1, $this->fetchedEncoding);

		return new Compare($contents1, $contents2, $size);
	}

	function cleanup($html) {
		$p1 = mb_convert_encoding('<!----------- Собственно произведение --------------->', $this->fetchedEncoding);
		$p2 = '<!--------------------------------------------------->';

		$i1 = strpos($html, $p1) + strlen($p1);
		$i2 = strpos($html, $p2, $i1);
		$html = substr($html, $i1, $i2 - $i1);
		$html = preg_replace('/(<!-+[^>]+>)/', '', $html);
		$html = preg_replace('/(<!-+|--+>)/', '', $html);

		return $html;
	}

	function size($html) {
		$p1 = mb_convert_encoding('- Блок описания произведения (слева вверху) -', $this->fetchedEncoding);
		$p2 = '</small>';
		$i1 = strpos($html, $p1);
		$i2 = strpos($html, $p2, $i1);
		$matched = substr($html, $i1, $i2 - $i1);
		preg_match('/\. (\d+)k\./i', $matched, $m);

		return @intval($m[1]) * 1024;
	}

}

