<?php

namespace Wepa\Core\Database\seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Wepa\Core\Models\Seo;
use Wepa\Core\Models\Site;


class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$this->call([
	        AdminUserSedder::class,
	        MenuSedder::class,
	        RoleSeeder::class,
	        FileManagerSeeder::class,
			SeoSeeder::class,
			SiteSeeder::class
        ]);
    }
}
