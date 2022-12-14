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
	 * @return void
	 */
	public function deleteFiles(): void
	{
		foreach(MakeInstallCommand::files() as $file) {
			if(file_exists($file)) {
				unlink($file);
			}
		}
	}
	
	/**
	 * @return void
	 */
	public function extractFiles(): void
	{
		$zip = new ZipArchive();
		$filePath = __DIR__ . '/../../../install.zip';
		if($zip->open($filePath) === true) {
			$zip->extractTo(base_path());
		}
		$zip->close();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle(): void
	{
		$this->backupFiles();
		$this->info('Backup: ' . base_path('core_install_backup.zip'));
//		$this->deleteFiles();
		$this->extractFiles();
	}
	
	/**
	 * @return void
	 */
	public function backupFiles(): void
	{
		$zip = new ZipArchive();
		$filePath = base_path('core_install_backup.zip');
		
		if(!file_exists($filePath)) {
			if($zip->open($filePath, ZipArchive::CREATE) === true) {
				foreach(MakeInstallCommand::files() as $file) {
					if(file_exists($file)) {
						copy($file, $file . '.backup');
						$relativeNameInFile = str_replace(base_path()
							. DIRECTORY_SEPARATOR,
							'',
							$file);
						$this->info('-- ' . $relativeNameInFile);
						$zip->addFile($file, $relativeNameInFile);
					}
				}
			}
			
			$zip->close();
		}
	}
}
