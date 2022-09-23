<?php

namespace Wepa\Core\Http\Controllers\Mixed;


use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
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
	                       mixed  $files = [],
	                       array  $props = [],
	                       array  $share = []): Response
	{
		$defatultShare = [
			'locale'      => app()->getLocale(),
			'locales'     => config('core.locales'),
			'translation' => $this->translation($files),
			'appName'     => config('app.name'),
		];
		
		$this->share(array_merge($defatultShare, $share));
		
		return Inertia::render($view, $props);
	}
	
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
		if(is_array($translation)){
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