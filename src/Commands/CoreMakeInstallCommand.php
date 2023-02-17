<?php

namespace Wepa\Core\Commands;


use File;
use Illuminate\Console\Command;


class CoreMakeInstallCommand extends CoreInstallCommand
{
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Core copy instalation files from base proyect.';
	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'core:make-install';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(): int
	{
		$files = $this->files();
		
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
			$from = $file['from'];
			$to = $file['to'];
			
			File::ensureDirectoryExists(dirname($to));
			if($file['type'] === 'directory') {
				File::copyDirectory($from, $to);
			} else {
				File::copy($from, $to);
			}
		}
		
		return self::SUCCESS;
	}
}
