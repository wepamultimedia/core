<?php

namespace Wepa\Core\Database\seeders;

use Illuminate\Database\Seeder;

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
        ]);
    }
}
