<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

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
    protected $signature = 'core:update {--framework}';

    public function handle(): int
    {
        $this->call('migrate');
        $this->call('vendor:publish', ['--tag' => 'core', '--force' => true]);
        $process = Process::fromShellCommandline('npm i -D vuex@next @vueuse/core vue-inline-svg@next vue-screen@next @inertiajs/progress tailwind-scrollbar sass');
        $process->run();
        $this->info($process->getOutput());

        return self::SUCCESS;
    }
}
