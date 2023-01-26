<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;
use ZipArchive;

class CoreMakeInstallCommand extends Command
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
    protected $signature = 'core:makeInstall';

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
     * @return array
     */
    public static function files(): array
    {
        return [
            resource_path('js/app.js'),
            resource_path('css/app.css'),
            base_path('tailwind.config.js'),
            base_path('vite.config.js'),
            app_path('Models/User.php'),
            app_path('Providers/AuthServiceProvider.php'),
            app_path('Providers/AppServiceProvider.php'),
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filePath = __DIR__.'/../../../install.zip';
        $files = self::files();

        foreach ($files as $file) {
            $relativeNameInFile = str_replace(base_path().DIRECTORY_SEPARATOR,
                '',
                $file);
            $this->warn($relativeNameInFile);
        }
        $this->warn('');
        $this->warn('--------------------');
        $this->confirm('Are you sure you want to replace the installation files? Check one by one from the list as they will be overwritten.');
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $zip = new ZipArchive();

        if ($zip->open($filePath, ZipArchive::CREATE) === true) {
            foreach ($files as $file) {
                $relativeNameInFile = str_replace(base_path().DIRECTORY_SEPARATOR,
                    '',
                    $file);
                if (file_exists($file)) {
                    $this->info('-- '.$relativeNameInFile);
                    $zip->addFile($file, $relativeNameInFile);
                } else {
                    $this->warn('-- '.$relativeNameInFile);
                }
            }
        }

        $zip->close();
    }
}
