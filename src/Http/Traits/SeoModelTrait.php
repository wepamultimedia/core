<?php

namespace Wepa\Core\Http\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Wepa\Core\Events\SeoModelDestroyedEvent;
use Wepa\Core\Events\SeoModelRequestEvent;
use Wepa\Core\Events\SeoModelSavedEvent;
use Wepa\Core\Models\Seo;

trait SeoModelTrait
{
    private array $_seoParams = [];

    public static function boot()
    {
        parent::boot();

        static::deleted(function (Model $model) {
            SeoModelDestroyedEvent::dispatch($model);
        });

        static::saving(function () {
            SeoModelRequestEvent::dispatch();
        });

        static::saved(function (Model $model) {
            SeoModelSavedEvent::dispatch($model);
        });
    }

    public function seo(): HasOne
    {
        return $this->hasOne(Seo::class, 'model_id', 'id')
            ->where('model_type', '=', self::class)
            ->withDefault($this->seoDefaultParams());
    }

    abstract public function seoDefaultParams(): array;

    public function seoAddParams(array $params): void
    {
        $this->_seoParams = array_merge($this->_seoParams, $params);
    }

    public function seoParams(): Attribute
    {
        return Attribute::make(
            get: function () {
                $params = collect($this->seoDefaultParams())
                    ->merge($this->_seoParams);

                if ($this->seoRequestParams()) {
                    $params = $params->merge(['request_params' => $this->seoRequestParams()]);
                }
                if ($this->seoRouteParams()) {
                    $params = $params->merge(['route_params' => $this->seoRouteParams()]);
                }

                return $params->toArray();
            }
        );
    }

    abstract public function seoRequestParams(): array;

    abstract public function seoRouteParams(): array;
}
