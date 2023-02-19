<?php

namespace Wepa\Core\Http\Traits\Backend;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Support\Arr;
use Wepa\Core\Http\Requests\Backend\SeoInjectFormRequest;
use Wepa\Core\Models\Seo;

trait SeoControllerTrait
{
    /**
     * @return bool|mixed|null
     */
    protected function seoDestroy(int $seoId): mixed
    {
        return Seo::where('id', $seoId)->delete();
    }

    /**
     * @throws BindingResolutionException
     */
    protected function seoUpdateOrCreate(array $params = [], callable $callback = null): int
    {
        if ($id = request('seo')['id']) {
            $id = $this->seoUpdate($id, $params);
            if ($callback) {
                $callback($id);
            }

            return $id;
        } else {
            $seo = $this->seoStore($params);

            return $seo->id;
        }
    }

    /**
     * @throws BindingResolutionException
     */
    protected function seoUpdate(int $seoId = null,
                                 array $params = []): HigherOrderBuilderProxy|int|null
    {
        request()->request->add(['seo_id' => $seoId]);

        $request = app()->make(SeoInjectFormRequest::class);

        $excludeFilter = ['canonical' => true];

        $params = collect($request->seo)
            ->filter(function ($value, $key) use ($excludeFilter) {
                if ($value !== null or Arr::has($excludeFilter, $key)) {
                    return true;
                }

                return false;
            })
            ->merge($params)
            ->merge($request->seo['translations'])
            ->except(['translations'])
            ->toArray();

        $seo = Seo::where('id', $seoId)->first();
        $seo->update($params);

        return $seo->id;
    }
	
	/**
	 * @param array $params
	 *
	 * @return Seo
	 * @throws BindingResolutionException
	 */
    protected function seoStore(array $params = []): Seo
    {
        $request = app()->make(SeoInjectFormRequest::class);

        $params = collect($request->seo)
            ->filter()
            ->merge($params)
            ->merge($request->seo['translations'])
            ->except(['translations'])
            ->toArray();

        return Seo::create($params);
    }
}
