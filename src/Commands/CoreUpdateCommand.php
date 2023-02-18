<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Wepa\Core\Models\Menu;

class CoreUpdateCommand extends CoreInstallCommand
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Core install copy instalation files, execute migration databases';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:update';

    /**
     * @return int
     */
    public function handle(): int
    {
	    $this->call('migrate');
	    $this->call('vendor:publish', ['--tag' => 'core', '--force' => true]);
		$this->copyFiles();

        $process = Process::fromShellCommandline('npm i vuex@next @vueuse/core vue-inline-svg@next vue-screen@next @inertiajs/progress sass');
        $process->run();
        $this->info($process->getOutput());

        return self::SUCCESS;
    }
}
