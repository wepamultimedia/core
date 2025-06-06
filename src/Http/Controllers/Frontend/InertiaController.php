<?php

namespace Wepa\Core\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionMethod;
use Wepa\Core\Models\Seo;
use Wepa\Core\Models\SeoTranslation;

class InertiaController extends \Wepa\Core\Http\Controllers\InertiaController
{
    protected function addThemeNameToViewPath(string &$view): void
    {
        if ($theme = config('core.theme.frontend') and $theme !== '') {
            $themeView = 'Themes/'.ucfirst($theme).'/'.$view;
            if ($this->checkViewExist($themeView)) {
                $view = $themeView;
            }
        }
    }

    /**
     * @return Application|RedirectResponse|Redirector|void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function slugRedirect(Request $request, string $slug = null)
    {
        $seoQuery = Seo::with('translation');

        if ($slug) {
            if($seoTranslation = SeoTranslation::select(['locale', 'seo_id'])->whereSlug($slug)->first()){
                $seoQuery->whereHas('translations', function ($query) use ($slug) {
                    $query->where('slug', '<>', null)->where('slug', $slug);
                });
            } else {
                abort(404);
            }
        } else {
            $seoTranslation = SeoTranslation::where(function($query){
                $query->whereNull('slug')
                    ->orWhere('slug', '');
            })->whereHas('seo', function ($query) {
                $query->whereAlias('home');
            })->first();
        }

        if ($seoTranslation->locale !== app()->getLocale()) {
            // If it is different but I sell from a manual language change, I change the slug to the selected language and redirect.
            if (session()->get('switchLocale')
                and $seoSwitchLocale = SeoTranslation::whereSeoId($seoTranslation->seo_id)
                    ->whereLocale(app()->getLocale())->first()) {
                return redirect($seoSwitchLocale->slug ?? '/');
            } else {
                // if I do not come from a manual language change or the translation does not exist, I set the translation of the app to the language of the slug.
                app()->setLocale($seoTranslation->locale);
            }
        }

        $seo = $seoQuery->first();

        if($seo->translation->slug_redirect){
            return redirect($seo->translation->slug_redirect, $seo->redirect);
        }

        return $this->buildSlugRedirect($request, $seo);
    }

    public function buildSlugRedirect(Request $request, Seo $seo)
    {
        self::$seoLoaded = true;

        Inertia::share('seo', $seo);

        if ($request_params = $seo->request_params) {
            $request->merge($request_params);
        }

        if ($route_params = $seo->route_params) {
            foreach ($route_params as $paramName => $paramValue) {
                $request->route()->setParameter($paramName, $paramValue);
            }
        }

        $routeParameters = $request->route()->parametersWithoutNulls();

        if (class_exists($seo->controller)) {
            $reflectionController = new ReflectionClass($seo->controller);

            if ($reflectionController->hasMethod($seo->action)) {
                $method = new ReflectionMethod($seo->controller, $seo->action);
                $actionParameters = [];

                foreach ($method->getParameters() as $parameter) {
                    $parameterClass = $parameter->getType()->getName();
                    $parameterName = $parameter->getName();
                    if (is_subclass_of($parameterClass, Request::class) or $parameterClass === Request::class) {
                        try {
                            $methodRequest = app()->make($parameterClass);
                        } catch (ValidationException $exception) {
                            abort(404, $exception->getMessage());
                        }
                        $actionParameters[$parameterName] = $methodRequest;
                    } else {
                        foreach ($routeParameters as $routeParameterName => $routeParameterValue) {
                            if ($routeParameterName === $parameterName) {
                                if (class_exists($parameterClass)) {
                                    $actionParameters[$parameterName] = $parameterClass::find($routeParameterValue);
                                } else {
                                    $actionParameters[$parameterName] = $routeParameterValue;
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

        abort(404);
    }
}
