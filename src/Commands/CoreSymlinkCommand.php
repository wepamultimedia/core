<?php

namespace Wepa\Core\Commands;


use Illuminate\Console\Command;


class CoreSymlinkCommand extends BaseSymlinkCommand
{
	protected $signature = 'core:sl';
	protected string $package = 'core';
}
