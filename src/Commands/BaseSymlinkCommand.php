<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class BaseSymlinkCommand extends Command
{
    protected $description = 'Symbolic links';

    protected string $developer = 'wepamultimedia';

    protected string $package;

    protected array $paths = [
        'pages' => [],
        'public' => [],
        'components' => [],
        'unit' => [],
        'feature' => [],
    ];

    protected $signature = '';

    public function handle()
    {
        $this->definePaths();
        $choice = $this->choice('Symbolic link', ['Create', 'Delete']);

        if ($choice === 'Create') {
            $this->info('Creating symbolinc Links...');
            $this->newLine(1);
            $this->create();
        }

        if ($choice === 'Delete') {
            $this->newLine(1);
            $this->info('Deleting symbolinc Links...');
            $this->delete();
        }
    }

    protected function definePaths()
    {
        $packageName = ucfirst(Str::camel($this->package));
        $vendorPackageName = strtolower($this->package);

        $this->paths['pages'] = [
            'source' => base_path("vendor\\$this->developer\\$vendorPackageName\\resources\\js\\Pages"),
            'target' => resource_path("js\\Pages\\Vendor\\$packageName"),
        ];

        $this->paths['public'] = [
            'source' => base_path("vendor\\$this->developer\\$vendorPackageName\\resources\\dist"),
            'target' => public_path('vendor\\'.strtolower($packageName)),
        ];

        $this->paths['components'] = [
            'source' => base_path("vendor\\$this->developer\\$vendorPackageName\\resources\\js\\Components"),
            'target' => resource_path("js\\Vendor\\$packageName\\Components"),
        ];

        $this->paths['unit'] = [
            'source' => base_path("vendor\\$this->developer\\$vendorPackageName\\tests\\Unit"),
            'target' => base_path("tests\\Unit\\$packageName"),
        ];

        $this->paths['feature'] = [
            'source' => base_path("vendor\\$this->developer\\$vendorPackageName\\tests\\Feature"),
            'target' => base_path("tests\\Feature\\$packageName"),
        ];
    }

    protected function create()
    {
        $commands = [];
        foreach ($this->paths as $index => $path) {
            if (! file_exists($path['target']) and file_exists($path['source'])) {
                File::ensureDirectoryExists(dirname($path['target']));
                $commands[] = [
                    'command' => "mklink /D {$path['target']} {$path['source']}",
                    'info' => str_replace(base_path(), '', $path['target']),
                ];
            } elseif (file_exists($path['target']) and ! is_link($path['target']) and file_exists($path['source'])) {
                $delete = $this->choice("The destination {$path['target']} exist, do you want to rename _backup it?",
                    ['Yes', 'No']);

                if ($delete === 'Yes') {
                    rename($path['target'], $path['target'].'_backup');
                    $commands[] = [
                        'command' => "mklink /D {$path['target']} {$path['source']}",
                        'info' => str_replace(base_path(), '', $path['target']),
                    ];
                }
            }
        }

        foreach ($commands as $command) {
            $this->info(' + > '.$command['info']);
            shell_exec($command['command']);
        }
    }

    protected function delete()
    {
        foreach ($this->paths as $path) {
            if (file_exists($path['target']) and is_link($path['target'])) {
                $this->warn(' - > '.str_replace(base_path(), '', $path['target']));
                rmdir($path['target']);
            }
        }
    }
}
