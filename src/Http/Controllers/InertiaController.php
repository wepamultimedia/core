<?php

namespace Wepa\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;
use Wepa\Core\Models\Seo;

class InertiaController extends Controller
{
    protected static bool $seoLoaded = false;

    public string $packageName;

    protected string $title = '';

    protected string $menuScope = '';

    protected array $share = [];

    protected array $translations = [];

    public function jetrender(Request $request,
                              string $view,
                              mixed $tranlation = [],
                              array $props = []): Response
    {
        $this->buildRender($view, $tranlation);

        return Jetstream::inertia()->render($request, $view, $props);
    }

    protected function buildRender(string &$view,
                                   mixed $translation = []): void
    {
        $this->buildViewPath($view);
        $defatultShare = [
            'default' => [
                'env' => config('app.env'),
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
        $defaultTranslation = is_array($defaultTranslation = Lang::get('default'))
            ? $defaultTranslation : [];

        $packageDefaultTranslation = is_array($packageDefaultTranslation = Lang::get('core::default'))
            ? $packageDefaultTranslation : [];

        $packageTranslation = [];

        if ($this->packageName !== '') {
            $packageTranslation = is_array($packageTranslation = Lang::get($this->packageName.'::default'))
                ? $packageTranslation : [];
        }

        return array_merge($packageDefaultTranslation, $packageTranslation, $defaultTranslation);
    }

    protected function addShare(array $share): void
    {
        $this->share = array_merge($this->share, $share);
    }

    protected function publishShare(): void
    {
        Inertia::share($this->share);
    }

    public function render(string $view,
                           mixed $translation = [],
                           array $props = []): Response
    {
        $this->beforeRender();
        $this->buildRender($view, $translation);

        return Inertia::render($view, $props);
    }

    protected function beforeRender()
    {
        if (! self::$seoLoaded) {
            $this->addSeo(request()->route()->getName());
        }
    }

    public function addSeo(string $alias = null): void
    {
        self::$seoLoaded = true;

        $mainSeo = Seo::where('alias', 'home')->first();

        if ($alias and $seo = Seo::where('alias', $alias)->first()) {
            // Si tiene seo y no tiene la etiqueta noindex o nofollow defino lo contrario
            if (! $seo->robots) {
                $seo->robots = ['index', 'follow'];
            } else {
                if (! in_array('noindex', $seo->robots)) {
                    $seo->robots = array_merge($seo->robots, ['index']);
                }

                if (! in_array('nofollow', $seo->robots)) {
                    $seo->robots = array_merge($seo->robots, ['follow']);
                }
            }
        } else {
            if ($this->title) {
                $title = $this->title;
            } else {
                $prefix = request()->route()->getPrefix();
                $prefix = Str::replace(['/', '-', '_', 'admin'], ' ', $prefix);
                $prefix = trim($prefix);

                $title = Str::afterLast(request()->url(), '/');
                $title = Str::replace(['-', '_'], ' ', $title);
                $title = trim($title);

                if ($prefix !== '' and $prefix !== null) {
                    $title = $prefix.' '.$title;
                }

                $title = Str::title($title);
            }

            $seo = new Seo([
                'title' => $title,
                'robots' => ['noindex', 'nofollow'],
            ]);
        }

        $seo->image = $seo->image ?? $mainSeo->image;
        $seo->facebook_image = $seo->facebook_image ?? $seo->image ?? $mainSeo->image;
        $seo->twitter_image = $seo->twitter_image ?? $seo->image ?? $mainSeo->image;

        $seo->facebook_description = $seo->facebook_description ?? $seo->description ?? $mainSeo->description;
        $seo->twitter_description = $seo->twitter_description ?? $seo->description ?? $mainSeo->description;

        $this->addShare([
            'seo' => $seo->toArray(),
        ]);
    }
}
