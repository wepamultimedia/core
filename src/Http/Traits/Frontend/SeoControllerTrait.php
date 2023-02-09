<?php

namespace Wepa\Core\Http\Traits\Frontend;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Wepa\Core\Models\Seo;


trait SeoControllerTrait
{
	/**
	 * @param Request $request
	 * @param string $slug
	 *
	 * @return mixed|void
	 * @throws BindingResolutionException
	 * @throws ReflectionException
	 */
	protected function slugRedirect(Request $request, string $slug)
	{
		if($seo = Seo::whereTranslation('slug', $slug)->attrsToArray(['site'])->first()) {
			
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
