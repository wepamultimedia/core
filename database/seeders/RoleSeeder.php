<?php

namespace Wepa\Core\Database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'User']);
		
		Permission::create(['name' => 'admin.users.index']);
		Permission::create(['name' => 'admin.users.create']);
		Permission::create(['name' => 'admin.users.edit']);
		Permission::create(['name' => 'admin.users.destroy']);
    }
}
