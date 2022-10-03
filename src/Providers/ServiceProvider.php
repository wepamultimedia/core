<?php

namespace Wepa\Core\Providers;

use Database\Seeders\DatabaseSeeder;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
	/**
	 * @param array $seeders
	 *
	 * @return void
	 */
	protected function loadSeeders(array $seeders): void
	{
		$this->callAfterResolving(DatabaseSeeder::class, function($seeder) use ($seeders) {
			$seeder->call($seeders);
		});
	}
}