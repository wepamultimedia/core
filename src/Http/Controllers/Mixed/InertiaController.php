<?php

namespace Wepa\Core\Http\Controllers\Mixed;


use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
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
		$this->checkViewExist($view);
		
		$defatultShare = [
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
		if(Str::startsWith($view, '/')) {
			$view = Str::substrReplace($view, '', 0, 1);
		} else {
			$this->addPackageNameToViewPath($view);
			$this->addThemeNameToViewPath($view);
		}
	}
	
	/**
	 * @param string $view
	 *
	 * @return void
	 */
	private function addPackageNameToViewPath(string &$view): void
	{
		if(!Str::contains($view, '@' . $this->packageName)) {
			$view = '@' . strtolower($this->packageName) . '/' . $view;
		}
	}
	
	/**
	 * @param string $view
	 *
	 * @return void
	 */
	private function addThemeNameToViewPath(string &$view): void
	{
		$theme = config('core.theme');
		if($theme and $theme !== '') {
			if(!Str::contains($view, '#' . $theme)) {
				$view = '#' . strtolower($theme) . '/' . $view;
			}
		}
	}
	
	/**
	 * @param string $view
	 *
	 */
	private function checkViewExist(string &$view)
	{
		if(preg_match('/^[a-zA-Z]/', $view)) {
			if(File::exists(resource_path('js/Pages/' . $view . '.vue'))){
				return;
			}
		} else {
			$theme = config('core.theme');
			$path = Str::replace(['#' . strtolower($theme) . '/', '@' . $this->packageName . '/'], '', $view);

			// Check if theme view file exist
			if(preg_match('/^\#/', $view)) {
				if(File::exists(resource_path('views/themes/' . $theme . '/' . $this->packageName . '/' . $path . '.vue'))) {
					return;
				}
			}
			
			// Check if package view file exist
			if(File::exists(resource_path('views/' . strtolower($this->packageName) . '/' . $path . '.vue'))) {
				if(!preg_match('/^\@/', $view)) {
					$this->addPackageNameToViewPath($path);
					$view = $path;
				}
				
				return;
			}
		}
		
		abort(501, 'The view file { "' . $view . '" } does not exist');
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