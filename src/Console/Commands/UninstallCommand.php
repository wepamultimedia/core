<?php

namespace Wepa\Core\Console\Commands;


use Illuminate\Console\Command;
use ZipArchive;


class UninstallCommand extends Command
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
	protected $signature = 'core:uninstall';
	
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
		$zip = new ZipArchive();
		$filePath = base_path('core_install_backup.zip');
		if($zip->open($filePath) === true) {
			$zip->extractTo(base_path());
		}
		$zip->close();
	}
}
