<?php

namespace Wepa\Core\Listeners;


use Carbon\Carbon;
use Illuminate\Support\Arr;
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
        $seo = Seo::where('model_type', $event->model::class)
            ->where('model_id', $event->model->id)
            ->first();
        
        $data = $this->buildData($event);
        
        if ($seo) {
            $seo->update($data);
        } else {
            Seo::create($data);
        }
        
        SitemapUpdatedEvent::dispatch();
    }
    
    protected function buildData(SeoModelSavedEvent $event): array
    {
        $data = collect([
            'model_type' => $event->model::class,
            'model_id' => $event->model->id,
            'last_mod' => Carbon::now(),
        ]);
        
        if ($request = request('seo')) {
            $data = $data->merge($request)
                ->filter(function ($value, $key) {
                    return $value or $key === 'canonical';
                });
            
            if (Arr::exists($request, 'translations')) {
                $data = $data->merge($request['translations'])
                    ->except(['translations']);
            }
        }
        
        $event->model->seoAddParams($data->toArray());
        
        return $event->model->seoParams;
    }
}
