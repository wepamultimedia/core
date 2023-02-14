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
	protected $description = 'Create a symbolic link to publish view and component folders for the development environment only.';
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'core:install';
	
	/**
	 * @return int
	 */
	public function handle()
	{
		$this->call('migrate');
		$this->call('vendor:publish', ['--tag' => 'core']);
		$this->copyFiles();
		Menu::loadPackageItems('core');
		$this->call('db:seed', ['class' => 'Wepa\Core\Database\seeders\DefaultSeeder']);
		
		$process = Process::fromShellCommandline('npm i vuex@next @vueuse/core vue-inline-svg@next vue-screen@next @inertiajs/progress sass');
		$process->run();
		$this->info($process->getOutput());
		
		return self::SUCCESS;
	}
	
	/**
	 * @return void
	 */
	public function copyFiles(): void
	{
		$files = CoreMakeInstallCommand::files();
		foreach($files as $file) {
			
			$from = $file['from'];
			$to = __DIR__ . '/../../install/' . $file['to'];
			
			if(File::exists($file['from']) && !$this->fileIsEqual($to, $from)) {
				File::copy($file['from'], $file['from'] . '.backup');
			}
			
			File::ensureDirectoryExists(dirname($file['from']));
			File::copy($to, $from);
		}
	}
	
	/**
	 * @param $fileOne
	 * @param $fileTwo
	 *
	 * @return bool
	 */
	private function fileIsEqual($fileOne, $fileTwo): bool
	{
		return (filesize($fileOne) == filesize($fileTwo) && md5_file($fileOne) == md5_file($fileTwo));
	}
}
