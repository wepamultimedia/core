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
	 * @param int $seoId
	 *
	 * @return bool|mixed|null
	 */
	protected function seoDestroy(int $seoId): mixed
	{
		return Seo::where('id', $seoId)->delete();
	}
	
	/**
	 * @param array $params
	 * @param callable|null $callback
	 *
	 * @return int
	 * @throws BindingResolutionException
	 */
	protected function seoUpdateOrCreate(array $params = [], callable $callback = null): int
	{
		if($id = request('seo')['id']) {
			$id = $this->seoUpdate($id, $params);
			if($callback) {
				$callback($id);
			}
			
			return $id;
		} else {
			$seo = $this->seoStore($params);
			
			return $seo->id;
		}
	}
	
	/**
	 * @param int|null $seoId
	 * @param array $params
	 *
	 * @return HigherOrderBuilderProxy|int|null
	 * @throws BindingResolutionException
	 */
	protected function seoUpdate(int   $seoId = null,
	                             array $params = []): HigherOrderBuilderProxy|int|null
	{
		request()->request->add(['seo_id' => $seoId]);
		$request = app()->make(SeoInjectFormRequest::class);
		
		$excludeFilter = [
			'canonical',
			'autocomplete',
		];
		
		$params = collect($request->seo)
			->filter(function($value, $key) use ($excludeFilter) {
				if($value !== null or Arr::has($excludeFilter, $key)) {
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
	 * @param string $controller
	 * @param string $action
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
			->merge(['autocomplete' => false])
			->merge($params)
			->merge($request->seo['translations'])
			->except(['translations'])
			->toArray();
		
		return Seo::create($params);
	}
}
