<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Wepa\Core\Models\Menu;

class CoreDownCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Core maintenance mode';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:down';

    public function handle(): int
    {
        $this->call('down', ['--render' => 'maintenance', '--status' => 200, '--secret' => env('DOWN_SECRET', 5555), '--refresh' => '10']);

        return self::SUCCESS;
    }

}
