<?php

namespace Wepa\Core\Listeners;


use Carbon\Carbon;
use Wepa\Core\Events\SeoModelDestroyedEvent;
use Wepa\Core\Events\SeoModelSavedEvent;
use Wepa\Core\Events\SitemapUpdatedEvent;
use Wepa\Core\Http\Requests\Backend\SeoInjectFormRequest;
use Wepa\Core\Models\Seo;


class SeoModelListener
{
    public function destroy(SeoModelDestroyedEvent $event): void
    {
        if ($seo = Seo::where('model_type', $event->model::class)->where('model_id', $event->model->id)->first()) {
            $seo->delete();
            SitemapUpdatedEvent::dispatch();
        };
    }
    
    public function request()
    {
        app()->make(SeoInjectFormRequest::class);
    }
    
    public function saved(SeoModelSavedEvent $event): void
    {
        $seo = Seo::where('model_type', $event->model::class)
            ->where('model_id', $event->model->id)
            ->first();
        
        $data = $this->getData($event);
        
        if ($seo) {
            $seo->update($data);
        } else {
            Seo::create($data);
        }
        
        SitemapUpdatedEvent::dispatch();
    }
    
    protected function getData(SeoModelSavedEvent $event): array
    {
        return collect(request('seo'))
            ->filter(function ($value, $key) {
                return $value or $key === 'canonical';
            })
            ->merge($event->model->seoRequestParams())
            ->merge($event->model->seoRouteParams())
            ->merge([
                'model_type' => $event->model::class,
                'model_id' => $event->model->id,
                'last_mod' => Carbon::now(),
            ])
            ->merge(request('seo')['translations'])
            ->except(['translations'])
            ->toArray();
    }
}
