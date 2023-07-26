<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Wepa\Core\Models\Seo;
use Wepa\Core\Models\Site;

class SitemapGenerateCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $site = Site::first();

        $sitemap = Sitemap::create();
        $url = Url::create(route('home'))
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(1);

        if ($site->seo->image) {
            $url->addImage($site->seo->image, $site->seo->image_alt);
        }

        $sitemap->add($url);

        Seo::all()->each(function ($seoItem) use ($sitemap) {
            if (! $seoItem->robots || ! in_array('noindex', $seoItem->robots)) {
                $url = Url::create(env('APP_URL').'/'.$seoItem->slug)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);
                if ($seoItem->image) {
                    $url->addImage($seoItem->image, $seoItem->image_alt ?? '');
                }

                $sitemap->add($url);
            }
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
