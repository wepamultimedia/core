<?php

namespace Wepa\Core\Database\seeders;


use Carbon\Carbon;
use Illuminate\Database\Seeder;
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
		if($seo = !Seo::where('alias', 'home')->first()) {
			$seo = Seo::create([
				'package' => 'core',
				'alias' => 'home',
				'canonical' => true,
				'page_type' => 'website',
				'title' => 'Your title',
				'description' => 'Your description',
			]);
		}
		
		if(!Site::first()) {
			Site::create([
				'seo_id' => $seo->id,
				'updated_at' => $date = Carbon::now(),
				'created_at' => $date,
			]);
		}
	}
}
