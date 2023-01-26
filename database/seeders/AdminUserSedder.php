<?php

namespace Wepa\Core\Database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::create([
            'name' => 'System Admin',
            'email' => env('USER_ADMIN', 'admin@admin.com'),
            'password' => bcrypt(env('USER_ADMIN_PASSWORD', 'password')),
        ]);
    }
}
