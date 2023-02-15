<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Response;
use Wepa\Core\Http\Requests\Backend\SeoFormRequest;
use Wepa\Core\Http\Traits\Backend\SeoControllerTrait;
use Wepa\Core\Models\Seo;

class SeoController extends InertiaController
{
    use SeoControllerTrait;

    public string $packageName = 'core';

    public function destroy(Seo $seo): void
    {
        if ($seo->alias !== 'home') {
            $this->seoDestroy($seo->id);
        }
    }

    public function edit(Seo $seo): Response
    {
        return $this->render('Core/Backend/Seo/Edit', 'seo', compact(['seo']));
    }

    public function index(Request $request): Response
    {
        $routes = Seo::when($request->search, function ($query, $search) {
            $query->where('alias', 'LIKE', '%'.$search.'%')
                ->orWhereTranslationLike('slug', '%'.$search.'%');
        })
            ->where('alias', '<>', 'home')
            ->orWhereNull('alias')
            ->paginate();

        return $this->render('Core/Backend/Seo/Index', 'seo', compact(['routes']));
    }

    public function editHome(): Response
    {
        $seo = Seo::where('alias', 'home')->first();

        return $this->render('Core/Backend/Seo/Edit', 'seo', compact(['seo']));
    }

    public function store(SeoFormRequest $request): Redirector|Application|RedirectResponse
    {
        $excludeFilter = ['alias', 'canonical'];

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

        Seo::create($params);

        return redirect(route('admin.seo.index'));
    }

    public function create(): Response
    {
        return $this->render('Core/Backend/Seo/Create', 'seo');
    }

    public function update(SeoFormRequest $request, Seo $seo): Redirector|RedirectResponse|Application
    {
        $excludeFilter = ['alias', 'canonical', 'slug'];

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

        if ($seo->alias === 'home') {
            return redirect()->back();
        }

        return redirect(route('admin.seo.index'));
    }
}
