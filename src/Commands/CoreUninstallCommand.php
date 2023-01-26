<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;
use Wepa\Core\Models\Menu;
use ZipArchive;

class CoreUninstallCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unistall core package';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:uninstall';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        Menu::removePackageItems('core');

        $zip = new ZipArchive();
        $filePath = base_path('core_install_backup.zip');
        if ($zip->open($filePath) === true) {
            $zip->extractTo(base_path());
        }
        $zip->close();
    }
}
