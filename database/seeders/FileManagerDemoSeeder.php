<?php

namespace Wepa\Core\Database\seeders;

use Illuminate\Database\Seeder;
use Wepa\Core\Models\File;

class FileManagerDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        File::factory()->count(5)->create(['type_id' => 1]);
        File::factory()->count(20)->create();
    }
}
