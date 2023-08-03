<?php

namespace Wepa\Core\Database\seeders;

use App\Http\Controllers\MainController;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Wepa\Core\Models\Site;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Site::where('id', 1)->exists()) {
            $site = new Site([
                'updated_at' => $date = Carbon::now(),
                'created_at' => $date,
            ]);

            $site->seoAddParams([
                'package' => 'core',
                'alias' => 'home',
                'canonical' => true,
                'page_type' => 'website',
                'title' => 'Your title',
                'description' => 'Your description',
                'model_type' => Site::class,
                'model_id' => $site->id,
                'controller' => MainController::class,
                'action' => 'home',
            ]);

            $site->save();
        }
    }
}
