<?php

namespace Wepa\Core\Providers;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;


class CoreServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		/**
		 * Config
		 *
		 * Uncomment this function call to make the config file publishable using the 'config' tag.
		 */
		// $this->publishes([
		//     __DIR__.'/../../config/core.php' => config_path('core.php'),
		// ], 'config');
		
		/**
		 * Routes
		 *
		 * Uncomment this function call to load the route files.
		 * A web.php file has already been generated.
		 */
		$this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
		
		/**
		 * Translations
		 *
		 * Uncomment the first function call to load the translations.
		 * Uncomment the second function call to load the JSON translations.
		 * Uncomment the third function call to make the translations publishable using the 'translations' tag.
		 */
//		 $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'core');
		// $this->loadJsonTranslationsFrom(__DIR__.'/../../resources/lang', 'core');
		$this->publishes([
			__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/core'),
		], 'translations');
		
		/**
		 * Views
		 *
		 * Uncomment the first section to load the views.
		 * Uncomment the second section to make the view publishable using the 'view' tags.
		 */
		// $this->loadViewsFrom(__DIR__.'/../../resources/views', 'core');
		// $this->publishes([
		//     __DIR__.'/../../resources/views' => resource_path('views/vendor/core'),
		// ], 'views');
		$this->publishes([
			__DIR__ . '/../../resources/js/Pages' => resource_path('js/Pages'),
		], ['core', 'core-views']);
		
		/**
		 * Commands
		 *
		 * Uncomment this section to load the commands.
		 * A basic command file has already been generated in 'src\Console\Commands\MyPackageCommand.php'.
		 */
		// if ($this->app->runningInConsole()) {
		//     $this->commands([
		//         \Wepa\Core\Console\Commands\CoreCommand::class,
		//     ]);
		// }
		
		/**
		 * Public assets
		 *
		 * Uncomment this functin call to make the public assets publishable using the 'public' tag.
		 */
		$this->publishes([
			__DIR__ . '/../../public' => public_path('core'),
		], ['core', 'core-public']);
		
		/**
		 * Migrations
		 *
		 * Uncomment the first function call to load the migrations.
		 * Uncomment the second function call to make the migrations publishable using the 'migrations' tags.
		 */
		// $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
		// $this->publishes([
		//     __DIR__.'/../../database/migrations/' => database_path('migrations')
		// ], 'migrations');
		
		/*
		 * Inertia translations
		 */
		$locale = app()->getLocale();
		
		$translations = Cache::rememberForever("translations_$locale", function() use ($locale) {
			$phpTranslations = [];
			$jsonTranslations = [];
			
			if(File::exists(resource_path("lang/$locale"))) {
				$phpTranslations = collect(File::allFiles(resource_path("lang/$locale")))
					->filter(function($file) {
						return $file->getExtension() === 'php';
					})->flatMap(function($file) {
						$basename = Str::lower(Str::substr($file->getBasename(), 0, -4));
						
						return Arr::dot(File::getRequire($file->getRealPath()), "$basename.");
					})->toArray();
			}
			
			if(File::exists(resource_path("lang/$locale.json"))) {
				$jsonTranslations = json_decode(File::get(resource_path("lang/$locale.json")), true);
			}
			
			return array_merge($phpTranslations, $jsonTranslations);
		});
		
		Inertia::share([
			'locale'      => $locale,
			'translation' => $translations,
			'appName'     => env('APP_NAME'),
		]);
		
		
	}
	
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		/**
		 * Config file
		 *
		 * Uncomment this function call to load the config file.
		 * If the config file is also publishable, it will merge with that file
		 */
		// $this->mergeConfigFrom(
		//     __DIR__.'/../../config/core.php', 'core'
		// );
	}
}
