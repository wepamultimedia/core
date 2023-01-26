<?php

namespace Wepa\Core\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;


trait InertiaControllerTrait
{
	public string $packageName;
	protected array $share = [];
	protected array $translations = [];
	
	/**
	 * @param Request $request
	 * @param string $view
	 * @param mixed $tranlation
	 * @param array $props
	 * @param array $share
	 *
	 * @return Response
	 */
	public function jetrender(Request $request,
	                          string  $view,
	                          mixed   $tranlation = [],
	                          array   $props = [],
	                          array   $share = []): Response
	{
		$this->buildRender($view, $tranlation, $share);
		
		return Jetstream::inertia()->render($request, $view, $props);
	}
	
	/**
	 * @param string $view
	 * @param mixed $tranlation
	 * @param array $share
	 *
	 * @return void
	 */
	protected function buildRender(string $view,
	                               mixed  $tranlation = [],
	                               array  $share = []): void
	{
		$this->buildViewPath($view);
		
		$user = request()->user();
		
		if($user)
			$user->tokens()->where('name', 'inertia')->delete();
		
		$defatultShare = [
			'access_token' => $user ? $user->createToken('inertia')->plainTextToken : null,
			'theme' => config('core.theme'),
			'locale' => app()->getLocale(),
			'locales' => config('core.locales'),
			'translation' => $this->translation($tranlation),
			'appName' => config('app.name'),
			'baseUrl' => request()->root(),
		];
		
		$this->addShare($defatultShare);
		$this->addShare($share);
		$this->publishShare();
	}
	
	/**
	 * @param string $view
	 *
	 * @return void
	 */
	protected function buildViewPath(string &$view): void
	{
		$this->addThemeNameToViewPath($view);
		if(!$this->checkViewExist($view)) {
			abort(501, 'The view file { "' . $view . '" } does not exist');
		}
	}
	
	/**
	 * @param string $view
	 *
	 * @return void
	 */
	protected function addThemeNameToViewPath(string &$view): void
	{
		/*
		 if($theme = config('core.theme.[frontend | backend ]') and $theme !== '') {
			$themeView = 'Themes/' . ucfirst($theme) . '/' . $view;
			if($this->checkViewExist($themeView)) {
				$view = $themeView;
			}
		 }
		*/
	}
	
	/**
	 * @param string $view
	 *
	 * @return bool
	 */
	protected function checkViewExist(string $view): bool
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
	protected function translation($files = null): array
	{
		$locale = app()->getLocale();
		$translations = [];
		$prefix = $this->packageName !== '' ? $this->packageName . '::' : '';
		
		if($files) {
			if(is_array($files)) {
				foreach($files as $file) {
					if(Str::contains($file, '::')) {
						$translation = Lang::get($file);
					} else {
						$translation = Lang::get($prefix . $file);
					}
					if(is_array($translation)) {
						$translations = array_merge($translations,
							$translation);
					}
				}
			} else {
				$translation = Lang::get($prefix . $files);
				
				if(is_array($translation)) {
					$translations = $translation;
				}
			}
		}
		
		return array_merge($this->defaultTranslation($locale), $translations);
	}
	
	/**
	 * @param $locale
	 *
	 * @return array
	 */
	protected function defaultTranslation($locale): array
	{
		$translation = is_array($translation = Lang::get('core::default'))
			? $translation : [];
		
		$packageTranslation = [];
		
		if($this->packageName !== '') {
			$packageTranslation = is_array($packageTranslation = Lang::get($this->packageName . '::default'))
				? $packageTranslation : [];
		}
		
		return array_merge($translation, $packageTranslation);
	}
	
	protected function addShare(array $share)
	{
		$this->share = array_merge($this->share, $share);
	}
	
	/**
	 * @return void
	 */
	protected function publishShare(): void
	{
		Inertia::share($this->share);
	}
	
	/**
	 * @param string $view
	 * @param mixed|null $tranlation Translations files
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
		$this->buildRender($view, $tranlation, $share);
		
		return Inertia::render($view, $props);
	}
}
