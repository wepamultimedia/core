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
	                       mixed  $files = [],
	                       array  $props = [],
	                       array  $share = []): Response
	{
		
		$this->buildViewPath($view);
		$this->checkViewExist($view);
		
		$defatultShare = [
			'locale'      => app()->getLocale(),
			'locales'     => config('core.locales'),
			'translation' => $this->translation($files),
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
			$view = Str::substrReplace($view, '', 0, 0);
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
			$view = '@' . $this->packageName . '/' . $view;
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
				$view = '#' . $theme . '/' . $view;
			}
		}
	}
	
	/**
	 * @param string $view
	 *
	 */
	private function checkViewExist(string &$view)
	{
		// Check if theme view file exist
		$theme = config('core.theme');
		$path = Str::replace(['#' . $theme . '/', '@' . $this->packageName . '/'], '', $view);
		if(!File::exists(resource_path('js/Themes/' . $theme . '/Pages/' . $this->packageName . '/' . $path . '.vue'))) {
			if(!File::exists(resource_path('js/Vendor/' . ucfirst($this->packageName) . '/Pages/' . $path . '.vue'))) {
				if(!File::exists(resource_path('js/Pages/' . $path . '.vue'))) {
					abort(501, 'The view file does not exist');
				}
			} else {
				$this->addPackageNameToViewPath($path);
			}
			
			$view = $path;
		}
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
					$translations = array_merge($translations, $translation);
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
	
	/**
	 * @param string $locale
	 * @param string $fileName
	 *
	 * @return array
	 */
	private function fileLang(string $locale, string $fileName): array
	{
		$translations = [];
		
		if(File::exists($filePath = resource_path("/lang/vendor/{$this->packageName}/$locale/$fileName.php"))) {
			return Cache::rememberForever("translations_{$locale}_{$this->packageName}_{$fileName}",
				function() use ($fileName, $locale, $filePath) {
					$translations = File::getRequire($filePath);
					if(is_array($translations)) {
						return Arr::dot($translations);
					}
				});
		}
		
		return $translations;
	}
}