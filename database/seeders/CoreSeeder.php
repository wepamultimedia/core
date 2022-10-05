<?php

namespace Wepa\Core\Database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;



class CoreSeeder extends Seeder
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
	        AdminMenuSedder::class,
	        RoleSeeder::class
        ]);
    }
}
