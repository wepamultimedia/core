<?php

namespace Wepa\Core\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

trait InertiaControllerTrait
{
    public string $packageName;

    protected string $menuScope = '';

    protected array $share = [];

    protected array $translations = [];

    protected function addShare(array $share): void
    {
        $this->share = array_merge($this->share, $share);
    }

    public function jetrender(Request $request,
                              string $view,
                              mixed $tranlation = [],
                              array $props = []): Response
    {
        $this->buildRender($view, $tranlation);

        return Jetstream::inertia()->render($request, $view, $props);
    }

	/**
	 * @param string $view
	 * @param mixed $translation
	 *
	 * @return void
	 */
    protected function buildRender(string &$view,
                                   mixed $translation = []): void
    {
        $this->buildViewPath($view);

        $defatultShare = [
            'default' => [
                'theme' => config('core.theme'),
                'locale' => app()->getLocale(),
                'locales' => config('core.locales'),
                'appName' => config('app.name'),
                'baseUrl' => request()->root(),
                'translation' => $this->translation($translation),
                'storageUrl' => preg_replace('/\/$/', '', Storage::disk(config('filesystems.default'))->url('')),
            ],
        ];

        $this->addShare($defatultShare);
        $this->publishShare();
    }

    protected function buildViewPath(string &$view): void
    {
        $this->addThemeNameToViewPath($view);
        if (! $this->checkViewExist($view)) {
            abort(501, 'The view file { "'.$view.'" } does not exist');
        }
    }

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

    protected function checkViewExist(string $view): bool
    {
        if (File::exists(resource_path('js/Pages/'.$view.'.vue'))) {
            return true;
        }

        return false;
    }

    protected function translation($files = null): array
    {
        $locale = app()->getLocale();
        $translations = [];
        $prefix = $this->packageName !== '' ? $this->packageName.'::' : '';

        if ($files) {
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (Str::contains($file, '::')) {
                        $translation = Lang::get($file);
                    } else {
                        $translation = Lang::get($prefix.$file);
                    }
                    if (is_array($translation)) {
                        $translations = array_merge($translations,
                            $translation);
                    }
                }
            } else {
                $translation = Lang::get($prefix.$files);

                if (is_array($translation)) {
                    $translations = $translation;
                }
            }
        }

        return array_merge($this->defaultTranslation($locale), $translations);
    }

    protected function defaultTranslation($locale): array
    {
        $translation = is_array($translation = Lang::get('core::default'))
            ? $translation : [];

        $packageTranslation = [];

        if ($this->packageName !== '') {
            $packageTranslation = is_array($packageTranslation = Lang::get($this->packageName.'::default'))
                ? $packageTranslation : [];
        }

        return array_merge($translation, $packageTranslation);
    }

    protected function publishShare(): void
    {
        Inertia::share($this->share);
    }
    
    /**
     * @param  string  $view
     * @param  mixed  $translation
     * @param  array  $props
     *
     * @return Response
     */
    public function render(string $view,
                           mixed $translation = [],
                           array $props = []): Response
    {
        $this->beforeRender();
        $this->buildRender($view, $translation);

        return Inertia::render($view, $props);
    }
}
