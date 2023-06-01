<?php

namespace Wepa\Core\Listeners;


use Illuminate\Support\Arr;
use PHPUnit\Exception;
use Wepa\Core\Events\SeoModelCreatedEvent;
use Wepa\Core\Events\SeoModelUpdatedEvent;
use Wepa\Core\Models\Seo;


class SeoModelListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function create(SeoModelCreatedEvent $event): void
    {
        try {
            $params = collect(request('seo'))
                ->filter()
                ->merge($event->params)
                ->merge(request('seo')['translations'])
                ->except(['translations'])
                ->toArray();
            
            Seo::create($params);
            
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    
    /**
     * Handle the event.
     *
     * @param  object  $event
     *
     * @return void
     */
    public function handle(SeoModelCreatedEvent $event)
    {
        //
    }
    
    public function update(SeoModelUpdatedEvent $event): void
    {
        $excludeFilter = ['canonical' => true];
        
        if (request('seo') and request('seo')['id']) {
            $params = collect(request('seo'))
                ->filter(function ($value, $key) use ($excludeFilter) {
                    if ($value !== null or Arr::has($excludeFilter, $key)) {
                        return true;
                    }
                    
                    return false;
                })
                ->merge($event->params)
                ->merge(request('seo')['translations'])
                ->except(['translations'])
                ->toArray();
            
            $seo = Seo::where('model_type', $event->model::class)->where('model_id', $event->model->id)->first();
            $seo->update($params);
        }
    }
}
