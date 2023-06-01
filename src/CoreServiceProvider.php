<?php

namespace Wepa\Core;


use Config;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wepa\Core\Commands\CoreInstallCommand;
use Wepa\Core\Commands\CoreMakeInstallCommand;
use Wepa\Core\Commands\CoreSymlinkCommand;
use Wepa\Core\Commands\CoreUninstallCommand;
use Wepa\Core\Commands\CoreUpdateCommand;
use Wepa\Core\Commands\SitemapGenerateCommand;
use Wepa\Core\Database\seeders\DefaultSeeder;
use Wepa\Core\Events\SeoModelCreatedEvent;
use Wepa\Core\Events\SeoModelUpdatedEvent;
use Wepa\Core\Events\SitemapUpdatedEvent;
use Wepa\Core\Http\Middleware\Backend;
use Wepa\Core\Http\Middleware\Frontend;
use Wepa\Core\Http\Middleware\Locale;
use Wepa\Core\Jobs\GenerateSitemapJob;
use Wepa\Core\Listeners\SeoModelListener;
use Wepa\Core\Models\Permission;


class CoreServiceProvider extends PackageServiceProvider
{
    public function bootingPackage()
    {
        Event::listen(SitemapUpdatedEvent::class, GenerateSitemapJob::class);
        Event::listen(SeoModelCreatedEvent::class, [SeoModelListener::class, 'create']);
        Event::listen(SeoModelUpdatedEvent::class, [SeoModelListener::class, 'update']);
        
        $this->hasSeeders([DefaultSeeder::class]);
        $this->registerViews();
        
        // Assets
        $this->publishes([
            $this->package->basePath('/../resources/dist') => public_path("vendor/{$this->package->shortName()}"),
        ], ['core', 'core-assets']);
        
        $this->publishes([
            __DIR__.'/../src/CoreServiceProvider.php' => app_path('Providers/CoreServiceProvider.php'),
        ], ['core-provider']);
        
        // Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], ['core', 'core-migrations']);
        
        // JS Components
        $this->publishes([
            __DIR__.'/../resources/js/Components' => resource_path('js/Vendor/Core/Components'),
        ], ['core', 'core-components']);
        
        // JS Mixins
        $this->publishes([
            __DIR__.'/../resources/js/Mixins' => resource_path('js/Vendor/Core/Mixins'),
        ], ['core', 'core-mixins']);
        
        // JS Store
        $this->publishes([
            __DIR__.'/../_resources/js/Store' => resource_path('js/Store'),
        ], ['core', 'core-store']);
        
        // JS Pages
        $this->publishes([
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages/Vendor/Core'),
        ], ['core', 'core-pages']);
    }
    
    protected function hasSeeders(array $seeders): void
    {
        $this->callAfterResolving(DatabaseSeeder::class,
            function ($cb) use ($seeders) {
                $cb->call($seeders);
            });
    }
    
    public function registerViews(): void
    {
        Fortify::loginView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['default' => ['translation' => $translation]]);
            
            return Inertia::render('Vendor/Core/Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        });
        
        Fortify::requestPasswordResetLinkView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['default' => ['translation' => $translation]]);
            
            return Inertia::render('Vendor/Core/Auth/ForgotPassword', [
                'status' => session('status'),
            ]);
        });
        
        Fortify::resetPasswordView(function (Request $request) {
            $translation = Lang::get('core::auth');
            Inertia::share(['default' => ['translation' => $translation]]);
            
            return Inertia::render('Vendor/Core/Auth/ResetPassword', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });
        
        Fortify::registerView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['default' => ['translation' => $translation]]);
            
            return Inertia::render('Vendor/Core/Auth/Register');
        });
        
        Fortify::verifyEmailView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['default' => ['translation' => $translation]]);
            
            return Inertia::render('Vendor/Core/Auth/VerifyEmail', [
                'status' => session('status'),
            ]);
        });
        
        Fortify::twoFactorChallengeView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['default' => ['translation' => $translation]]);
            
            return Inertia::render('Vendor/Core/Auth/TwoFactorChallenge');
        });
        
        Fortify::confirmPasswordView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['default' => ['translation' => $translation]]);
            
            return Inertia::render('Vendor/Core/Auth/ConfirmPassword');
        });
    }
    
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('core')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasAssets()
            ->hasRoutes(['web', 'admin', 'api'])
            ->hasCommands([
                CoreSymlinkCommand::class,
                CoreInstallCommand::class,
                CoreUpdateCommand::class,
                CoreUninstallCommand::class,
                CoreMakeInstallCommand::class,
                SitemapGenerateCommand::class,
            ]);
        
        /**
         * Config file
         *
         * Uncomment this function call to load the config file.
         * If the config file is also publishable, it will merge with that file
         */
        $this->mergeConfigFrom(
            __DIR__.'/../config/core.php',
            'core'
        );
        
        // Configure middlewares
        app()['router']->aliasMiddleware('core.backend', Backend::class);
        app()['router']->aliasMiddleware('core.frontend', Frontend::class);
        app()['router']->aliasMiddleware('core.locale', Locale::class);
        app()['router']->prependMiddlewareToGroup('web', Locale::class);
        
        // Configure permission
        config()->set('permission.models.permission', Permission::class);
        
        // Configure translatable
        config()->set('translatable.use_fallback', true);
        config()->set('translatable.fallback_locale', config('app.locale'));
    }
}
