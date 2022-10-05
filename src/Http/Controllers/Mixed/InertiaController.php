<?php

namespace Wepa\Core\Http\Controllers\Mixed;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;
use Inertia\Response;


class InertiaController extends Controller
{
	public string $packageName;
	
	/**
	 * @param string $view
	 * @param mixed|null $files Translations files
	 * @param array $props
	 * @param array $share
	 *
	 * @return Response
	 */
	public function render(string $view,
	                       mixed  $tranlation = [],
	                       array  $props = [],
	                       array  $share = []): Response
	{
		
		$this->buildViewPath($view);
		
		$defatultShare = [
			'theme'       => config('core.theme'),
			'locale'      => app()->getLocale(),
			'locales'     => config('core.locales'),
			'translation' => $this->translation($tranlation),
			'appName'     => config('app.name'),
		];
		
		$this->share(array_merge($defatultShare, $share));
		
		return Inertia::render($view, $props);
	}
	
	/**
	 * @param string $view
	 *
	 * @return void
	 */
	private function buildViewPath(string &$view): void
	{
		if(!$this->addThemeNameToViewPath($view)) {
			if(!$this->checkViewExist($view)) {
				abort(501, 'The view file { "' . $view . '" } does not exist');
			}
		}
	}
	
	/**
	 * @param string $view
	 *
	 * @return bool
	 */
	private function addThemeNameToViewPath(string &$view): bool
	{
		if($theme = config('core.theme') and $theme !== '') {
			$themeView = 'Themes/' . ucfirst($theme) . '/' . $view;
			if($this->checkViewExist($themeView)) {
				$view = $themeView;
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * @param string $view
	 *
	 * @return bool
	 */
	private function checkViewExist(string $view): bool
	{
		if(File::exists(resource_path('js/Pages/' . $view . '.vue'))) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * @param $files
	 *
	 * @return array
	 */
	public function translation($files = null): array
	{
		$locale = app()->getLocale();
		$translations = [];
		
		if($files) {
			if(is_array($files)) {
				foreach($files as $file) {
					$translation = Lang::get($this->packageName . '::' . $file);
					if(is_array($translation)) {
						$translations = array_merge($translations, $translation);
					}
				}
			} else {
				$translation = Lang::get($this->packageName . '::' . $files);
				if(is_array($translation)) {
					$translations = $translation;
				}
			}
		}
		
		return array_merge($translations, $this->defaultTranslation($locale));
	}
	
	/**
	 * @param $locale
	 *
	 * @return array
	 */
	private function defaultTranslation($locale): array
	{
		$translation = Lang::get($this->packageName . '::default');
		if(is_array($translation)) {
			return $translation;
		}
		
		return [];
	}
	
	/**
	 * @param array|string|null $langFiles
	 * @param bool $loadDefaultLang
	 *
	 * @return void
	 */
	public function share(array $share): void
	{
		Inertia::share($share);
	}
}