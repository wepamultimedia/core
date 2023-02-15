<?php

namespace Wepa\Core\Database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! User::where('email', 'admin@admin.com')->first()) {
            User::create([
                'name' => 'System Admin',
                'email' => env('USER_ADMIN', 'admin@admin.com'),
                'password' => bcrypt(env('USER_ADMIN_PASSWORD', 'password')),
            ]);
        }
    }
}
