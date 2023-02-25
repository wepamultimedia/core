<?php

namespace Wepa\Core\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Wepa\Core\Models\Seo;


class InertiaController extends \Wepa\Core\Http\Controllers\InertiaController
{
	private static bool $seoLoaded = false;
	
	protected function addThemeNameToViewPath(string &$view): void
	{
		if($theme = config('core.theme.frontend') and $theme !== '') {
			$themeView = 'Themes/' . ucfirst($theme) . '/' . $view;
			if($this->checkViewExist($themeView)) {
				$view = $themeView;
			}
		}
	}
	
	protected function beforeRender(): void
	{
		if(!self::$seoLoaded) {
			$this->addSeo(request()->route()->getName());
		}
	}
	
	public function addSeo(string $alias): void
	{
		self::$seoLoaded = true;
		
		$mainSeo = Seo::where('alias', 'home')->first();
		
		if($seo = Seo::where('alias', $alias)->first()) {
			// Si tiene seo y no tiene la etiqueta noindex o nofollow defino lo contrario
			if(!$seo->robots) {
				$seo->robots = ['index', 'follow'];
			} else {
				if(!in_array('noindex', $seo->robots)) {
					$seo->robots = array_merge($seo->robots, ['index']);
				}
				
				if(!in_array('nofollow', $seo->robots)) {
					$seo->robots = array_merge($seo->robots, ['follow']);
				}
			}
		} else {
			$seo = new Seo([
				'robots' => ['noindex', 'nofollow'],
				'slug' => request()->route()->getName(),
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
	
	/**
	 * @return mixed|void
	 *
	 * @throws BindingResolutionException
	 * @throws ReflectionException
	 */
	protected function slugRedirect(Request $request, string $slug)
	{
		if($seo = Seo::whereHas('translations', function($query) use ($slug) {
			$query->where('slug', '<>', null)->where('slug', $slug);
		})->first()) {
			self::$seoLoaded = true;
			
			Inertia::share('seo', $seo);
			
			if($request_params = $seo->request_params) {
				$request->merge($request_params);
			}
			
			if($route_params = $seo->route_params) {
				foreach($route_params as $paramName => $paramValue) {
					$request->route()->setParameter($paramName, $paramValue);
				}
			}
			
			$routeParameters = $request->route()->parametersWithoutNulls();
			
			if(class_exists($seo->controller)) {
				$reflectionController = new ReflectionClass($seo->controller);
				
				if($reflectionController->hasMethod($seo->action)) {
					$method = new ReflectionMethod($seo->controller, $seo->action);
					$actionParameters = [];
					
					foreach($method->getParameters() as $parameter) {
						$parameterClass = $parameter->getType()->getName();
						$parameterName = $parameter->getName();
						if(is_subclass_of($parameterClass, Request::class) or $parameterClass === Request::class) {
							try {
								$methodRequest = app()->make($parameterClass);
							} catch(ValidationException $exception) {
								abort(404, $exception->getMessage());
							}
							$actionParameters[ $parameterName ] = $methodRequest;
						} else {
							foreach($routeParameters as $routeParameterName => $routeParameterValue) {
								if($routeParameterName === $parameterName) {
									if(class_exists($parameterClass)) {
										$actionParameters[ $parameterName ] = $parameterClass::find($routeParameterValue);
									} else {
										$actionParameters[ $parameterName ] = $routeParameterValue;
									}
									break;
								}
							}
						}
					}
					
					/* @property Controller $controller */
					$controller = new $seo->controller;
					
					return $controller->callAction($seo->action, $actionParameters);
				}
			}
		}
		
		abort(404);
	}
}
