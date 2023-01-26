<?php

namespace Wepa\Core\Http\Controllers\Frontend;




use Wepa\Core\Http\Traits\Frontend\SeoControllerTrait;


class InertiaController extends \Wepa\Core\Http\Controllers\InertiaController
{
	use SeoControllerTrait;
	
	
	/**
	 * @param string $view
	 *
	 * @return void
	 */
	protected function addThemeNameToViewPath(string &$view): void
	{
		if($theme = config('core.theme.frontend') and $theme !== '') {
			$themeView = 'Themes/' . ucfirst($theme) . '/' . $view;
			if($this->checkViewExist($themeView)) {
				$view = $themeView;
			}
		}
	}
}
