<?php

namespace Wepa\Core\Console\Commands;


use Illuminate\Console\Command;


class SymlinkCommand extends Command
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
	protected $signature = 'core:create_links';
	
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
		$developerName = 'wepamultimedia';
		$packageName = ucfirst('core');
		
		$sourcePagesPath = base_path("vendor\\$developerName\\$packageName\\resources\\js\\Pages");
		$targetPagesPath = resource_path("js\\Pages\\$packageName");
		
		$sourcePublicPath = base_path("vendor\\$developerName\\$packageName\\public");
		$targetPublicPath = base_path('public\\' . strtolower($packageName));
		
		$sourceJsPath = base_path("vendor\\$developerName\\$packageName\\resources\\js");
		$targetJsPath = resource_path("js\\$packageName");

		$sourceTestUnitPath = base_path("vendor\\$developerName\\$packageName\\tests\\Unit");
		$targetTestUnitPath = base_path("tests\\Unit\\$packageName");

		$sourceTestFeaturePath = base_path("vendor\\$developerName\\$packageName\\tests\\Feature");
		$targetTestFeaturePath = base_path("tests\\Feature\\$packageName");

		$commands = [
			"mklink /D $targetPagesPath $sourcePagesPath",
			"mklink /D $targetPublicPath $sourcePublicPath",
			"mklink /D $targetJsPath $sourceJsPath",
			"mklink /D $targetTestUnitPath $sourceTestUnitPath",
			"mklink /D $targetTestFeaturePath $sourceTestFeaturePath",
		];

		foreach($commands as $command) {
			$this->info($command);
			shell_exec($command);
		}
	}
}
