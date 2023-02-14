<?php

namespace Wepa\Core\Commands;


use Illuminate\Console\Command;
use ZipArchive;
use File;
use function PHPUnit\Framework\directoryExists;


class CoreMakeInstallCommand extends Command
{
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Core instalation files.';
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'core:make-install';
	
	/**
	 * @return array
	 */
	public static function files(): array
	{
		return [
			// Resources
			['from' => resource_path('js/app.js'), 'to' => 'resources/js/app.js.stub'],
			['from' => resource_path('css/app.css'), 'to' => 'resources/css/app.css.stub'],
			['from' => resource_path('scss/app.scss'), 'to' => 'resources/scss/app.scss.stub'],
			['from' => resource_path('views/app.blade.php'), 'to' => 'resources/views/app.blade.php.stub'],
			['from' => resource_path('js/Pages/Home.vue'), 'to' => 'resources/js/Pages/Home.vue.stub'],
			
			// Resources Lang
			['from' => resource_path('lang/en.json'), 'to' => 'resources/lang/en.json.stub'],
			['from' => resource_path('lang/es.json'), 'to' => 'resources/lang/es.json.stub'],
			['from' => resource_path('lang/en/auth.php'), 'to' => 'resources/lang/en/auth.php.stub'],
			['from' => resource_path('lang/en/default.php'), 'to' => 'resources/lang/en/default.php.stub'],
			['from' => resource_path('lang/en/notifications.php'), 'to' => 'resources/lang/en/notifications.php.stub'],
			['from' => resource_path('lang/en/pagination.php'), 'to' => 'resources/lang/en/pagination.php.stub'],
			['from' => resource_path('lang/en/passwords.php'), 'to' => 'resources/lang/en/passwords.php.stub'],
			['from' => resource_path('lang/en/validation.php'), 'to' => 'resources/lang/en/validation.php.stub'],
			['from' => resource_path('lang/es/auth.php'), 'to' => 'resources/lang/es/auth.php.stub'],
			['from' => resource_path('lang/es/default.php'), 'to' => 'resources/lang/es/default.php.stub'],
			['from' => resource_path('lang/es/notifications.php'), 'to' => 'resources/lang/es/notifications.php.stub'],
			['from' => resource_path('lang/es/pagination.php'), 'to' => 'resources/lang/es/pagination.php.stub'],
			['from' => resource_path('lang/es/passwords.php'), 'to' => 'resources/lang/es/passwords.php.stub'],
			['from' => resource_path('lang/es/validation.php'), 'to' => 'resources/lang/es/validation.php.stub'],
			
			// Root
			['from' => base_path('tailwind.config.js'), 'to' => 'tailwind.config.js.stub'],
			['from' => base_path('vite.config.js'), 'to' => 'vite.config.js.stub'],
			
			// Config
			['from' => base_path('config/app.php'), 'to' => 'config/app.php.stub'],
			['from' => base_path('config/fortify.php'), 'to' => 'config/fortify.php.stub'],
			['from' => base_path('config/jetstream.php'), 'to' => 'config/jetstream.php.stub'],
			['from' => base_path('config/sanctum.php'), 'to' => 'config/sanctum.php.stub'],
			
			// App
			['from' => app_path('Models/User.php'), 'to' => 'app/Models/User.php.stub'],
			['from' => app_path('Providers/AuthServiceProvider.php'), 'to' => 'app/Providers/AuthServiceProvider.php.stub'],
			['from' => app_path('Providers/AppServiceProvider.php'), 'to' => 'app/Providers/AppServiceProvider.php.stub'],
			['from' => app_path('Http/Controllers/MainController.php'), 'to' => 'app/Http/Controllers/MainController.php.stub'],
			
			// Routes
			['from' => base_path('routes/web.php'), 'to' => 'routes/web.php.stub'],
			['from' => base_path('routes/web.php'), 'to' => 'routes/web.php.stub'],
			
			// Public
			['from' => public_path('images/logo.svg'), 'to' => 'public/images/logo.svg'],
			['from' => public_path('images/logo-white.svg'), 'to' => 'public/images/logo-white.svg'],
		];
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$files = self::files();
		
		foreach($files as $file) {
			$relativeNameInFile = str_replace(base_path() . DIRECTORY_SEPARATOR,
				'',
				$file['from']);
			$this->warn($relativeNameInFile);
		}
		$this->line('');
		$this->warn('--------------------');
		$this->confirm('Are you sure you want to replace the installation files? Check one by one from the list as they will be overwritten.');
		
		foreach($files as $file) {
			File::ensureDirectoryExists(dirname(__DIR__ . '/../../install/' . $file['to']));
			File::copy($file['from'], __DIR__ . '/../../install/' . $file['to']);
		}
	}
}
