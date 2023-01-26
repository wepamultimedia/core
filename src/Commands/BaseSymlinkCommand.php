<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;

class BaseSymlinkCommand extends Command
{
    protected $description = 'Symbolic links';

    protected string $developer = 'wepamultimedia';

    protected string $package;

    protected array $paths = [
        'pages' => [],
        'public' => [],
        'js' => [],
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
        $packageName = ucfirst($this->package);

        $this->paths['pages'] = [
            'source' => base_path("vendor\\$this->developer\\$packageName\\resources\\js\\Pages"),
            'target' => resource_path("js\\Pages\\$packageName"),
        ];

        $this->paths['public'] = [
            'source' => base_path("vendor\\$this->developer\\$packageName\\resources\\dist"),
            'target' => public_path('vendor\\'.strtolower($packageName)),
        ];

        $this->paths['js'] = [
            'source' => base_path("vendor\\$this->developer\\$packageName\\resources\\js"),
            'target' => resource_path("js\\$packageName"),
        ];

        $this->paths['unit'] = [
            'source' => base_path("vendor\\$this->developer\\$packageName\\tests\\Unit"),
            'target' => base_path("tests\\Unit\\$packageName"),
        ];

        $this->paths['feature'] = [
            'source' => base_path("vendor\\$this->developer\\$packageName\\tests\\Feature"),
            'target' => base_path("tests\\Feature\\$packageName"),
        ];
    }

    protected function create()
    {
        $commands = [];
        foreach ($this->paths as $index => $path) {
            if (! file_exists($path['target']) and file_exists($path['source'])) {
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
