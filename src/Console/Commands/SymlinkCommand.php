<?php

namespace Wepa\Core\Console\Commands;

use Doctrine\DBAL\Logging\Middleware;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;


class SymlinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:symlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link to publish view and component folders for the development environment only.';

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
        $sourceViewPath = base_path('packages/core/resources/views');
		$targetViewPath = resource_path('cores');
		$cmd = 'ln -s "' . $targetViewPath . '" "' . $sourceViewPath . '"';
	
	    $process = Process::fromShellCommandline($cmd);
	
	    $processOutput = '';
	
	    $captureOutput = function ($type, $line) use (&$processOutput) {
		    $processOutput .= $line;
	    };
	
	    $process->setTimeout(null)
		    ->run($captureOutput);
	
	    if ($process->getExitCode()) {
		    $exception = $cmd . " - " . $processOutput;
		    report($exception);
	    }
	
	    dd($processOutput);
    }
}
