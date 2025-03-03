<?php

namespace Wepa\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Wepa\Core\Models\SeoTranslation
 *
 * @property string $keyword
 * @property string $title
 * @property string $slug
 * @property string $slug_prefix
 * @property string $slug_suffix
 * @property string $slug_redirect
 * @property string $description
 * @property string $image_title
 * @property string $image_alt
 * @property string $facebook_title
 * @property string $facebook_description
 * @property string $facebook_image_title
 * @property string $facebook_image_alt
 * @property string $twitter_title
 * @property string $twitter_description
 * @property string $twitter_image_title
 * @property string $twitter_image_alt
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SeoTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoTranslation query()
 *
 * @mixin \Eloquent
 */
class SeoTranslation extends Model
{
    public $timestamps = false;

    public $casts = [
        'slug_prefix' => 'array'
    ];

    protected $fillable = [
        'keyword',
        'title',
        'slug',
        'slug_prefix',
        'slug_suffix',
        'slug_redirect',
        'description',
        'image_title',
        'image_alt',
        'facebook_title',
        'facebook_description',
        'facebook_image_title',
        'facebook_image_alt',
        'twitter_title',
        'twitter_description',
        'twitter_image_title',
        'twitter_image_alt',
    ];

    protected $table = 'core_seo_translations';

    public function seo(): BelongsTo
    {
        return $this->belongsTo(Seo::class, 'seo_id', 'id');
    }
}
