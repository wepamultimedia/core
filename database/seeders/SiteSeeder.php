<?php

namespace Wepa\Core\Database\seeders;


use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Wepa\Core\Http\Controllers\Backend\SiteController;
use Wepa\Core\Models\Seo;
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
            'controller' => SiteController::class,
            'action' => 'edit',
        ]);
    
        $site->save();
    }
}
