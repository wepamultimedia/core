<?php

namespace Wepa\Core\Providers;


use Illuminate\Routing\Router;
use Wepa\Core\Console\Commands\InstallCommand;
use Wepa\Core\Console\Commands\SymlinkCommand;
use Wepa\Core\Console\Commands\UninstallCommand;
use Wepa\Core\Database\seeders\CoreSeeder;
use Wepa\Core\Http\Middleware\Backend;
use Wepa\Core\Http\Middleware\Frontend;


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
		$this->publishes([
			__DIR__ . '/../../config/core.php' => config_path('core.php'),
		],
			[
				'core',
				'core-config',
			]);
		
		/**
		 * Routes
		 *
		 * Uncomment this function call to load the route files.
		 * A web.php file has already been generated.
		 */
		$this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
		$this->loadRoutesFrom(__DIR__ . '/../../routes/console.php');
		
		/**
		 * Translations
		 *
		 * Uncomment the first function call to load the translations.
		 * Uncomment the second function call to load the JSON translations.
		 * Uncomment the third function call to make the translations publishable using the 'translations' tag.
		 */
		$this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'core');
		
		// $this->loadJsonTranslationsFrom(__DIR__.'/../../resources/lang', 'core');
		$this->publishes([
			__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/core'),
		], ['core', 'core-lang']);
		
		/**
		 * Views
		 *
		 * Uncomment the first section to load the views.
		 * Uncomment the second section to make the view publishable using the 'view' tags.
		 */
		//		$this->loadViewsFrom(__DIR__.'/../../resources/views', 'core');
		// $this->publishes([
		// 	__DIR__ . '/../../resources/views' => resource_path('views/core'),
		// ], ['core','core-views']);
		
		/** Overwrites */
		$this->publishes([
			__DIR__ . '/../../overwrite/resources/js/app.js'                  => resource_path('js/app.js'),
			__DIR__ . '/../../overwrite/resources/css/app.css'                => resource_path('css/app.css'),
			__DIR__ . '/../../overwrite/tailwind.config.js'                   => base_path('tailwind.config.js'),
			__DIR__ . '/../../overwrite/vite.config.js'                       => base_path('vite.config.js'),
			__DIR__ . '/../../overwrite/app/Providers/AuthServiceProvider.ow' => app_path('Providers/AuthServiceProvider.php'),
			__DIR__ . '/../../overwrite/app/Providers/AppServiceProvider.ow'  => app_path('Providers/AppServiceProvider.php'),
			__DIR__ . '/../../overwrite/app/Middleware/Authenticate.ow'       => app_path('Http/Middleware/Authenticate.php'),
			__DIR__ . '/../../overwrite/config/jetstream.ow'                  => base_path('config/jetstream.php'),
			__DIR__ . '/../../overwrite/config/fortify.ow'                    => base_path('config/fortify.php'),
			__DIR__ . '/../../overwrite/config/permission.ow'                 => base_path('config/permission.php'),
		], ['core', 'core-overwrite']);
		
		/** JS */
		$this->publishes([
			__DIR__ . '/../../resources/js' => resource_path('js/Core'),
			__DIR__ . '/../../resources/js/Pages' => resource_path('js/Pages/Core'),
		], ['core', 'core-js']);
		
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
		], ['core', 'core-public',]);
		
		/**
		 * Migrations
		 *
		 * Uncomment the first function call to load the migrations.
		 * Uncomment the second function call to make the migrations publishable using the 'migrations' tags.
		 */
		$this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
		// $this->publishes([
		//     __DIR__.'/../../database/migrations/' => database_path('migrations')
		// ], 'migrations');
		
		$this->loadSeeders([
			CoreSeeder::class,
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
		
		/** @var Router $router */
		$router = $this->app['router'];
		$this->commands([
			SymlinkCommand::class,
			InstallCommand::class,
			UninstallCommand::class,
		]);
		
		$router->aliasMiddleware('core.backend', Backend::class);
		$router->aliasMiddleware('core.frontend', Frontend::class);
	}
}
