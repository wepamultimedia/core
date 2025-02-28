<?php

namespace Wepa\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Wepa\Blog\Models\Post;
use Wepa\Core\Models\File;
use Wepa\Core\Models\Seo;
use Wepa\PropertyCatalog\Models\Property;
use Wepa\PropertyCatalog\Models\PropertyFile;
use Wepa\PropertyCatalog\Models\PropertyImage;

class CoreMigrateDigitalOceanToPublicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:migrate-do-to-public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rootDoDir = env('DO_ROOT', null);
        $endDoEndPoint = env('DO_END_POINT', null);

        if ($rootDoDir and $endDoEndPoint) {
            $searchOne = env('DO_ENDPOINT') . '/' . $rootDoDir . '/';
            $searchTwo = 'file-manager/';

            // Property catalog
            if (class_exists('Wepa\PropertyCatalog\Models\Property')) {
                Property::all()->each(function ($property) use ($searchOne, $searchTwo) {
                    $property->cover = Str::replace($searchOne, '', $property->cover);
                    $property->cover = Str::replace($searchTwo, '', $property->cover);
                    $property->save();
                });

                PropertyFile::all()->each(function ($file) use ($searchOne, $searchTwo) {
                    $file->file_url = Str::replace($searchOne, '', $file->file_url);
                    $file->file_url = Str::replace($searchTwo, '', $file->file_url);
                    $file->save();
                });

                PropertyImage::all()->each(function ($propertyImage) use ($searchOne, $searchTwo) {
                    $propertyImage->image_url = Str::replace($searchOne, '', $propertyImage->image_url);
                    $propertyImage->image_url = Str::replace($searchTwo, '', $propertyImage->image_url);
                    $propertyImage->save();
                });
            }

            // Blog
            if (class_exists('Wepa\Blog\Models\Post')) {
                Post::all()->each(function ($post) use ($searchOne, $searchTwo) {
                    $post->cover = Str::replace($searchOne, '', $post->cover);
                    $post->cover = Str::replace($searchTwo, '', $post->cover);
                    $post->save();
                });
            }

            // Core
            File::all()->each(function ($file) use ($searchOne, $searchTwo) {

                $file->file = Str::replace($searchOne, '', $file->file);
                $file->file = Str::replace($searchTwo, '', $file->file);

                $file->url = Str::replace($searchOne, '', $file->url);
                $file->url = Str::replace($searchTwo, '', $file->url);

                if ($file->type_id === 1) {
                    $file->file = null;
                    $file->url = null;
                }
                $file->save();
            });

            Seo::all()->each(function ($seo) use ($searchOne, $searchTwo) {
                $seo->image = Str::replace($searchOne, '', $seo->image);
                $seo->facebook_image = Str::replace($searchOne, '', $seo->facebook_image);
                $seo->twitter_image = Str::replace($searchOne, '', $seo->twitter_image);
                $seo->image = Str::replace($searchTwo, '', $seo->image);
                $seo->facebook_image = Str::replace($searchTwo, '', $seo->facebook_image);
                $seo->twitter_image = Str::replace($searchTwo, '', $seo->twitter_image);
                $seo->save();
            });
        }
    }
}
