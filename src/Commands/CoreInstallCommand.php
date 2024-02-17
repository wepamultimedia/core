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
    protected $description = 'Core install copy instalation files, execute migration databases and seeders';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:install';

    public function handle(): int
    {
        $this->call('migrate');
        $this->call('vendor:publish', ['--tag' => 'core-framework', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'core', '--force' => true]);

        Menu::loadPackageItems('core');

        $this->call('db:seed', ['class' => 'Wepa\Core\Database\seeders\DefaultSeeder']);

        $process = Process::fromShellCommandline('npm i -D vuex@next @vueuse/core vue-inline-svg@next vue-screen@next @inertiajs/progress tailwind-scrollbar sass ckeditor5-classic-core');
        $process->run();
        $this->info($process->getOutput());

        return self::SUCCESS;
    }

}
