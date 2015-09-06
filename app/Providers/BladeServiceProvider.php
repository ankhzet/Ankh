<?php namespace Ankh\Providers;

use View;
use Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider {

	function openMatcherPattern($tag, $prev = '', $post = '') {
		return '/' . $prev . '\B@(' . $tag . ')([ \t]*)(\( (( (?>[^()]+) | (?3) )* ) \))?' . $post . '/x';
	}

	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function boot() {

		Blade::extend(function($view, $compiler) {
			return preg_replace('/\@kept\((.+?)\)/', '<?php if (!isset($exclude) || array_search(\'${1}\', $exclude) === false) { ?>', $view);
		});

		Blade::extend(function($view, $compiler) {
			return preg_replace('/\@endkept[ \t]*/', '<?php } ?>', $view);
		});


		Blade::extend(function($view, $compiler) {
			return preg_replace('/\@admin\((.*?)\)\s*/',
				'<?php $_a_user = isset($_a_user) ? $_a_user : Auth::user(); if (($_a_user != null) && $_a_user->isAdmin()) : ?>', $view);
		});
		Blade::extend(function($view, $compiler) {
			return preg_replace('/\s*\@endadmin/', '<?php endif; ?>', $view);
		});

		Blade::extend(function($view, $compiler) {
			return preg_replace('/\s*\@i-menu\((.*?)\)\s*/',
				'<ul class="inline-menu ${1}">', $view);
		});
		Blade::extend(function($view, $compiler) {
			return preg_replace('/\s*\@endmenu\s*/', '</ul>', $view);
		});

		Blade::extend(function($view, $compiler) {
			$pattern = $this->openMatcherPattern('m-item');
			return preg_replace_callback($pattern, function ($matches) {
				preg_match('/(.+?)\s*,(.+)/', $matches[4], $m);
				return '<li><a href="<?php echo ' . $m[2] . '; ?>"><?php echo app(\'translator\')->get(' . $m[1] . '); ?></a></li>';
			}, $view);
		});

		Blade::extend(function($view, $compiler) {
			$pattern = $this->openMatcherPattern('m-delete');
			return preg_replace_callback($pattern, function ($matches) {
				preg_match('/(.+?)\s*,(.+)/', $matches[4], $m);
				return '<li><a href="<?php echo ' . $m[2] . '; ?>" data-method="delete"><?php echo app(\'translator\')->get(' . $m[1] . '); ?></a></li>';
			}, $view);
		});


		Blade::extend(function($view, $compiler) {
			return preg_replace('/\@lbr/', json_decode('" "'), $view);
		});


		Blade::extend(function($view, $compiler) {
			return preg_replace_callback($this->openMatcherPattern('samlib'), function($matches) {
				static $rel = 0;
				$entity = trim($matches[4]);
				$var = '$_relative_' . (++$rel);

				$link = "<?php if ({$var} = ({$entity}->absoluteLink())) : echo '"
				. "<span class=\"link samlib\">"
				. "<a href=\"http://samlib.ru'. {$var} . '\" target=\"_blank\">' . {$var} . '</a>"
				. "</span>'; endif ?>";

				return $link;
			}, $view);
		});

	}

	public function register() {
	}

}
