<?php

namespace Wepa\Core\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Wepa\Core\Models\Menu;


class CoreInstallCommand extends Command
{
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Core install copy instalation files, execute migration databases and seeders';
	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'core:install';
	
	/**
	 * @return int
	 */
	public function handle(): int
	{
		$this->call('migrate');
		$this->call('vendor:publish', ['--tag' => 'core', '--force' => true]);
		$this->copyFiles();
		Menu::loadPackageItems('core');
		$this->call('db:seed', ['class' => 'Wepa\Core\Database\seeders\DefaultSeeder']);
		
//		$process = Process::fromShellCommandline('npm i vuex@next @vueuse/core vue-inline-svg@next vue-screen@next @inertiajs/progress sass');
//		$process->run();
//		$this->info($process->getOutput());
		
		return self::SUCCESS;
	}
	
	public function copyFiles(): void
	{
		$files = $this->files();
		foreach($files as $file) {
			$from = $file['from'];
			$to = $file['to'];
			
			File::ensureDirectoryExists(dirname($file['from']));
			
			if($file['type'] === 'directory') {
				File::copyDirectory($to, $from);
			} else {
				File::copy($to, $from);
			}
		}
	}
	
	/**
	 * @return array[]
	 */
	public function files(): array
	{
		return [
			// Resources
			['type' => 'file', 'from' => resource_path('js/app.js'), 'to' => __DIR__ . '/../../_resources/js/app.js'],
			[
				'type' => 'file', 'from' => resource_path('css/app.css'),
				'to' => __DIR__ . '/../../_resources/css/app.css',
			],
			[
				'type' => 'file', 'from' => resource_path('scss/app.scss'),
				'to' => __DIR__ . '/../../_resources/scss/app.scss',
			],
			[
				'type' => 'file', 'from' => resource_path('js/Pages/Home.vue'),
				'to' => 'resources/js/Pages/Home.vue',
			],
			['type' => 'directory', 'from' => resource_path('views'), 'to' => __DIR__ . '/../../_resources/views'],
			
			// Lang
			['type' => 'directory', 'from' => resource_path('lang'), 'to' => __DIR__ . '/../../_resources/lang'],
			
			// Root
			[
				'type' => 'file', 'from' => base_path('tailwind.config.js'),
				'to' => __DIR__ . '/../../_root/tailwind.config.js',
			],
			['type' => 'file', 'from' => base_path('vite.config.js'), 'to' => __DIR__ . '/../../_root/vite.config.js'],
			
			// Config
			['type' => 'file', 'from' => base_path('config/app.php'), 'to' => __DIR__ . '/../../_config/app.php'],
			[
				'type' => 'file', 'from' => base_path('config/fortify.php'),
				'to' => __DIR__ . '/../../_config/fortify.php',
			],
			[
				'type' => 'file', 'from' => base_path('config/jetstream.php'),
				'to' => __DIR__ . '/../../_config/jetstream.php',
			],
			[
				'type' => 'file', 'from' => base_path('config/sanctum.php'),
				'to' => __DIR__ . '/../../_config/sanctum.php',
			],
			[
				'type' => 'file', 'from' => base_path('config/filesystems.php'),
				'to' => __DIR__ . '/../../_config/filesystems.php',
			],
			
			// Models
			['type' => 'directory', 'from' => app_path('Models'), 'to' => __DIR__ . '/../../_app/Models'],
			[
				'type' => 'file',
				'from' => app_path('Providers/AuthServiceProvider.php'),
				'to' => __DIR__ . '/../../_app/Providers/AuthServiceProvider.php',
			],
			[
				'type' => 'file',
				'from' => app_path('Providers/AppServiceProvider.php'),
				'to' => __DIR__ . '/../../_app/Providers/AppServiceProvider.php',
			],
			[
				'type' => 'file',
				'from' => app_path('Http/Controllers/MainController.php'),
				'to' => __DIR__ . '/../../_app/Http/Controllers/MainController.php',
			],
			[
				'type' => 'directory', 'from' => app_path('Http/Requests'),
				'to' => __DIR__ . '/../../_app/Http/Requests',
			],
			['type' => 'directory', 'from' => app_path('Mail'), 'to' => __DIR__ . '/../../_app/Mail'],
			
			// Routes
			['type' => 'directory', 'from' => base_path('routes'), 'to' => __DIR__ . '/../../_routes'],
		];
	}
}
