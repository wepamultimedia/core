<?php

namespace Wepa\Core\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Wepa\Core\Events\SeoModelDestroyedEvent;
use Wepa\Core\Events\SeoModelSavedEvent;
use Wepa\Core\Events\SitemapUpdatedEvent;
use Wepa\Core\Http\Requests\Backend\SeoInjectFormRequest;
use Wepa\Core\Models\Seo;
use Wepa\Core\Models\SeoTranslation;

class SeoModelListener
{
    public function destroy(SeoModelDestroyedEvent $event): void
    {
        if ($seo = Seo::where('model_type', $event->model::class)->where('model_id', $event->model->id)->first()) {
            $seo->delete();

            SitemapUpdatedEvent::dispatch();
        }
    }

    public function request()
    {
        if (request('seo')) {
            app()->make(SeoInjectFormRequest::class);
        }
    }

    public function saved(SeoModelSavedEvent $event): void
    {
//        $seo = Seo::where('model_type', $event->model::class)
//            ->where('model_id', $event->model->id)
//            ->first();

        $data = $this->buildData($event);

        $seo = Seo::updateOrCreate(['model_type' => $event->model::class, 'model_id' => $event->model->id], $data);

        $this->updateSlugs($seo);

        SitemapUpdatedEvent::dispatch();
    }

    protected function buildData(SeoModelSavedEvent $event): array
    {
        $data = collect([]);

        if ($request = request('seo')) {
            $data = $data->merge($request)
                ->except('slug_prefix')
                ->filter(function ($value, $key) {
                    return $value or $key === 'canonical';
                });

            if (Arr::exists($request, 'translations')) {
                $data = $data->merge($request['translations'])
                    ->except(['translations']);
            }
        }

        $data = $data->merge([
            'last_mod' => Carbon::now(),
        ]);

        $event->model->seoAddParams($data->toArray());

        return $event->model->seoParams;
    }

    public function updateSlugs(Seo $seo): void
    {
        $translations = SeoTranslation::where('seo_id', $seo->id)->get();
        $request = request('seo');

        foreach ($translations as $translation) {
            $langPrefix = '';

            if(Str::startsWith($translation->slug_suffix, '/')){
                $translation->slug = Str::substr($translation->slug_suffix, 1);
            } else {
                if (count(config('core.locales')) > 1) {
                    if($translation->seo_id !== 1 or ($translation->seo_id === 1 and $translation->locale !== config('core.default_locale'))){
                        $langPrefix = $translation->locale . '/';
                    }
                }

                if ($request['slug_prefix'] and array_key_exists($translation->locale, $request['slug_prefix'])) {
                    $translation->slug_prefix = $request['slug_prefix'][$translation->locale];
                    $translation->slug = $langPrefix . $translation->slug_prefix . '/' . $translation->slug_suffix;
                } else {
                    $translation->slug = $langPrefix . $translation->slug_suffix;
                }
            }
            $translation->save();
        }
    }
}
