<?php

namespace Wepa\Core\Console\Commands;


use Illuminate\Console\Command;
use ZipArchive;


class InstallCommand extends Command
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
	protected $signature = 'core:install';
	
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
		$this->backupFiles();
		$this->info('Backup: ' . base_path('core_install_backup.zip'));
		$this->deleteFiles();
		$this->extractFiles();
	}
	
	public function files()
	{
		return [
			resource_path('js/app.js'),
			resource_path('css/app.css'),
			base_path('tailwind.config.js'),
			base_path('vite.config.js'),
			app_path('Models/User.php'),
			app_path('Providers/AuthServiceProvider.php'),
			app_path('Providers/AppServiceProvider.php'),
			app_path('Http/Middleware/Authenticate.php'),
			base_path('config/database.php'),
			base_path('config/jetstream.php'),
			base_path('config/fortify.php'),
			base_path('config/permission.php'),
		];
	}
	
	public function deleteFiles()
	{
		foreach($this->files() as $file) {
			unlink($file);
		}
	}
	
	public function backupFiles()
	{
		$zip = new ZipArchive();
		$filePath = base_path('core_install_backup.zip');
		
		if(!file_exists($filePath)) {
			if($zip->open($filePath, ZipArchive::CREATE) === true) {
				foreach($this->files() as $file) {
					if(file_exists($file)) {
						$relativeNameInFile = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
						$this->info('-- ' . $relativeNameInFile);
						$zip->addFile($file, $relativeNameInFile);
					}
				}
			}
			
			$zip->close();
		}
	}
	
	public function extractFiles()
	{
		$zip = new ZipArchive();
		$filePath = __DIR__ . '/../../../install.zip';
		if($zip->open($filePath) === true) {
			$zip->extractTo(base_path());
		}
		$zip->close();
	}
}
