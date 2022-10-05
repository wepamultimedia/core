<?php

namespace Wepa\Core\Console\Commands;


use Illuminate\Console\Command;
use ZipArchive;


class MakeInstallCommand extends Command
{
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a symbolic link to publish view and component folders for the development environment only.';
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'core:makeInstall';
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$filePath = __DIR__ . '/../../../install.zip';

		if(file_exists($filePath)){
			unlink($filePath);
		}
		
		$zip = new ZipArchive();
		
		$filesToBackup = [
			resource_path('js/app.js'),
			resource_path('css/app.css'),
			base_path('tailwind.config.js'),
			base_path('vite.config.js'),
			app_path('Providers/AuthServiceProvider.php'),
			app_path('Providers/AppServiceProvider.php'),
			app_path('Http/Middleware/Authenticate.php'),
			base_path('config/jetstream.php'),
			base_path('config/fortify.php'),
			base_path('config/permission.php'),
		];
		
		if($zip->open($filePath, ZipArchive::CREATE) === true) {
			foreach($filesToBackup as $file) {
				$relativeNameInFile = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
				$this->info('-- ' . $relativeNameInFile);
				$zip->addFile($file, $relativeNameInFile);
			}
		}
		
		$zip->close();
	}
}
