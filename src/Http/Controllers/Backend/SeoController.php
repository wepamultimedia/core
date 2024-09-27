<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Response;
use Wepa\Core\Http\Requests\Backend\SeoFormRequest;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;
use Wepa\Core\Models\Seo;
use Wepa\Core\Models\SeoTranslation;

class SeoController extends InertiaController
{
    use SeoControllerTrait;

    public string $packageName = 'core';
    private string $cacheTag = 'core_seo';

    public function destroy(Seo $seo): void
    {
        if ($seo->alias !== 'home') {
            $this->seoDestroy($seo->id);

            cache()->tags($this->cacheTag)->flush();
        }
    }

    public function edit(int $seo): Response
    {
        $seo = cache()
            ->tags($this->cacheTag)
            ->remember($this->cacheTag . ":" . $seo, 60 * 60 * 24, function () use ($seo) {
           return Seo::find($seo);
        });

        return $this->render('Vendor/Core/Backend/Seo/Edit', 'seo', compact(['seo']));
    }

    public function index(Request $request): Response
    {
        $cacheName = $this->cacheTag;

        if ($request->search !== null) {
            $cacheName .= '.' . Str::slug($request->search);
        }

        if ($request->get('page')) {
            $cacheName .= '.' . Str::slug($request->page);
        }

        $routes = cache()
            ->tags($this->cacheTag)
            ->remember($cacheName, 60 * 60 * 24, function () use ($request) {
                return Seo::when($request->search, function ($query, $search) {
                    $query->where('alias', 'LIKE', '%' . $search . '%')
                        ->orWhereTranslationLike('slug', '%' . $search . '%');
                })->paginate();
            });

        return $this->render('Vendor/Core/Backend/Seo/Index', 'seo', compact(['routes']));
    }

    public function store(SeoFormRequest $request): Redirector|Application|RedirectResponse
    {
        $excludeFilter = ['alias', 'canonical', 'slug', 'route_params', 'request_params'];

        $params = collect($request->all())
            ->merge(['package' => $this->packageName])
            ->filter(function ($value, $key) use ($excludeFilter) {
                if ($value !== null or in_array($key, $excludeFilter)) {
                    return true;
                }

                return false;
            })
            ->merge($request['translations'])
            ->except(['translations'])
            ->toArray();

        $seo = Seo::create($params);

        $this->updateSlugs($seo);

        cache()->tags($this->cacheTag)->flush();

        return redirect(route('admin.seo.index'));
    }

    public function updateSlugs(Seo $seo): void
    {
        $translations = SeoTranslation::where('seo_id', $seo->id)->get();
        $request = request();
        foreach ($translations as $translation) {
            if($request['slug_prefix']){
                $translation->slug_prefix = $request['slug_prefix'];
                $translation->slug = Arr::join($request['slug_prefix'], '/') . '/' . $translation->slug_suffix;
                $translation->save();
            } else {
                $translation->slug = $translation->slug_suffix;
                $translation->save();
            }
        }
    }

    public function create(): Response
    {
        return $this->render('Vendor/Core/Backend/Seo/Create', 'seo');
    }

    public function update(SeoFormRequest $request, int $seo): Redirector|RedirectResponse|Application
    {
        $seo = cache()
            ->tags($this->cacheTag)
            ->remember($this->cacheTag . ":" . $seo, 60 * 60 * 24, function () use ($seo) {
                return Seo::find($seo);
            });

        $excludeFilter = ['alias', 'canonical', 'slug', 'route_params', 'request_params'];

        $params = collect($request->all())
            ->filter(function ($value, $key) use ($excludeFilter) {
                if ($value !== null or in_array($key, $excludeFilter)) {
                    return true;
                }

                return false;
            })
            ->merge($request['translations'])
            ->except(['translations'])
            ->toArray();

        $seo->update($params);

        $this->updateSlugs($seo);

        cache()->tags($this->cacheTag)->flush();

        if ($seo->alias === 'home') {
            return redirect()->back();
        }

        return redirect(route('admin.seo.index'));
    }
}
