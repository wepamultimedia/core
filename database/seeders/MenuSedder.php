<?php

namespace Wepa\Core\Database\seeders;

use Illuminate\Database\Seeder;
use Wepa\Core\Models\Menu;


class MenuSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Menu::loadPackageItems('core');
    }
}
