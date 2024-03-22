<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;

class CoreUpCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Core exit maintenance mode';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:up';

    public function handle(): int
    {
        $this->call('up');

        return self::SUCCESS;
    }

}
