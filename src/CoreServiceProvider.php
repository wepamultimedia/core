<?php

namespace Wepa\Core;

use Config;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wepa\Core\Console\Commands\CoreInstallCommand;
use Wepa\Core\Console\Commands\CoreMakeInstallCommand;
use Wepa\Core\Console\Commands\CoreSymlinkCommand;
use Wepa\Core\Console\Commands\CoreUninstallCommand;
use Wepa\Core\Database\seeders\DefaultSeeder;
use Wepa\Core\Http\Middleware\Backend;
use Wepa\Core\Http\Middleware\Frontend;
use Wepa\Core\Models\Permission;

class CoreServiceProvider extends PackageServiceProvider
{
    /**
     * @param  array  $seeders
     * @return void
     */
    protected function hasSeeders(array $seeders): void
    {
        $this->callAfterResolving(DatabaseSeeder::class,
            function ($cb) use ($seeders) {
                $cb->call($seeders);
            });
    }

    /**
     * @return void
     */
    public function registerViews(): void
    {
        Fortify::loginView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['translation' => $translation]);

            return Inertia::render('Core/Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['translation' => $translation]);

            return Inertia::render('Core/Auth/ForgotPassword', [
                'status' => session('status'),
            ]);
        });

        Fortify::resetPasswordView(function (Request $request) {
            $translation = Lang::get('core::auth');
            Inertia::share(['translation' => $translation]);

            return Inertia::render('Core/Auth/ResetPassword', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });

        Fortify::registerView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['translation' => $translation]);

            return Inertia::render('Core/Auth/Register');
        });

        Fortify::verifyEmailView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['translation' => $translation]);

            return Inertia::render('Core/Auth/VerifyEmail', [
                'status' => session('status'),
            ]);
        });

        Fortify::twoFactorChallengeView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['translation' => $translation]);

            return Inertia::render('Core/Auth/TwoFactorChallenge');
        });

        Fortify::confirmPasswordView(function () {
            $translation = Lang::get('core::auth');
            Inertia::share(['translation' => $translation]);

            return Inertia::render('Core/Auth/ConfirmPassword');
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
            ->runsMigrations()
            ->hasMigrations([
                '01_create_menu_tables',
                '02_create_permission_tables',
                '03_create_permissions_translations_tables',
                '04_create_seo_tables',
                '05_create_filemanager_tables',

            ])
            ->hasCommands([
                CoreSymlinkCommand::class,
                CoreInstallCommand::class,
                CoreUninstallCommand::class,
                CoreMakeInstallCommand::class,
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
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('core.backend', Backend::class);
        $router->aliasMiddleware('core.frontend', Frontend::class);

        // Configure permission
        config()->set('permission.models.permission', Permission::class);

        // Configure translatable
        config()->set('translatable.use_fallback', true);

        // Configure Diditalocean spaces
        if (! config('filesystem.disks.do')) {
            $key = env('DO_ACCESS_KEY_ID');
            $secret = env('DO_SECRET_ACCESS_KEY');
            $region = env('DO_DEFAULT_REGION');
            $bucket = env('DO_BUCKET');
            $root = env('DO_ROOT');
            $endpoint = env('DO_ENDPOINT');

            if ($key and $secret and $region and $bucket and $endpoint and $root) {
                Config::set('filesystems.disks.do', [
                    'driver' => 's3',
                    'key' => $key,
                    'secret' => $secret,
                    'region' => $region,
                    'bucket' => $bucket,
                    'root' => $root,
                    'directory_separator' => '/',
                    'endpoint' => $endpoint,
                    'throw' => true,
                ]);

                config()->set('filesystems.default', 'do');
            }
        }
    }

    public function bootingPackage()
    {
        $this->hasSeeders([DefaultSeeder::class]);
        $this->registerViews();

        // Install
        $this->publishes([
            __DIR__.'/../install/resources/lang' => resource_path('lang'),
        ], ['core-install']);

        // JS
        $this->publishes([
            __DIR__.'/../resources/js' => resource_path('js/Core'),
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages/Core'),
        ], ['core', 'core-install', 'core-js']);
    }
}
