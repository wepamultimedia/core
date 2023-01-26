<?php

namespace Wepa\Core\Http\Controllers\Backend;

class InertiaController extends \Wepa\Core\Http\Controllers\InertiaController
{
	/**
	 * @param string $view
	 *
	 * @return void
	 */
	protected function addThemeNameToViewPath(string &$view): void
	{
		if($theme = config('core.theme.backend') and $theme !== '') {
			$themeView = 'Themes/' . ucfirst($theme) . '/' . $view;
			if($this->checkViewExist($themeView)) {
				$view = $themeView;
			}
		}
	}
}
