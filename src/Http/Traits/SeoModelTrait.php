<?php

namespace Wepa\Core\Http\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Wepa\Blog\Http\Controllers\Frontend\PostController;
use Wepa\Core\Events\{SeoModelRequestEvent, SeoModelSavedEvent};
use Wepa\Core\Models\Seo;


trait SeoModelTrait
{
    /* https://ogp.me/#types */
    /* https://www.contentpowered.com/blog/xml-sitemap-priority-changefreq/ */
    public array $seoRobots = [];
    public bool $seoCanonical = true;
    public string $seoPageType = 'website';
    public string $seoArticleType = 'article';
    public string $seoController;
    public string $seoAction;
    public string $seoChangeFreq;
    public float $seoPriority;
    
    public static function boot()
    {
        parent::boot();
        
        static::saving(function(){
            SeoModelRequestEvent::dispatch();
        });
        
        static::saved(function(Model $model){
            $params = array_merge($model->seoRouteParams(), $model->seoRequestParams());
            SeoModelSavedEvent::dispatch($model, $params);
        });
    }
    
    public function seo(): HasOne
    {
        return $this->hasOne(Seo::class, 'model_id', 'id')
            ->where('model_type', '=', self::class)
            ->withDefault($this->seoDefault());
    }
    
    public function seoDefault(): array
    {
        return collect([
            'controller' =>  $this->seoController,
            'action' =>  $this->seoAction,
            'route_params' => $this->seoRouteParams(),
            'request_params' => $this->seoRequestParams(),
            'robots' => $this->seoRobots,
            'canonical' => $this->seoCanonical,
            'page_type' => $this->seoPageType,
            'article_type' => $this->seoArticleType,
            'change_freq' => $this->seoChangeFreq,
            'priority' => $this->seoPriority,
            'model_type' => self::class
        ])
        ->filter()
        ->toArray();
    }
    
    public function seoRouteParams(): array
    {
        return $this->id ? ['route_params' => []] : [];
    }
    
    public function seoRequestParams(): array
    {
        return $this->id ? ['request_params' => []] : [];
    }
}
