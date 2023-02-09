<?php

namespace Wepa\Core\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Wepa\Core\Models\Menu;
use ZipArchive;


class CoreInstallCommand extends Command
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
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle(): void
	{
		Menu::loadPackageItems('core');
		$this->copyFiles();
	}
	
	/**
	 * @return void
	 */
	public function copyFiles(): void
	{
		$files = CoreMakeInstallCommand::files();
		foreach($files as $file) {
			if(File::exists($file['from'])){
				File::copy($file['from'], $file['from'] . '.backup');
			}
			
			File::copy(__DIR__ . '/../../install/' . $file['to'], $file['from']);
		}
	}
}
