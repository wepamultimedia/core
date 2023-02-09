<?php

namespace Wepa\Core\Models;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;
use Wepa\Core\Http\Traits\TranslationsTrait;


/**
 * Wepa\Core\Models\Seo
 *
 * @property int $id
 * @property string $keyword
 * @property string $title
 * @property string $slug
 * @property array $route_params
 * @property array $request_params
 * @property array $package
 * @property array $alias
 * @property string $canonical
 * @property string $robots
 * @property string $description
 * @property string $image
 * @property string $image_title
 * @property string $image_alt
 * @property string $facebook_title
 * @property string $facebook_description
 * @property string $facebook_image
 * @property string $facebook_image_title
 * @property string $facebook_image_alt
 * @property string $twitter_title
 * @property string $twitter_description
 * @property string $twitter_image
 * @property string $twitter_image_title
 * @property string $twitter_image_alt
 * @property string $controller
 * @property string $action
 * @property string $page_type
 * @property string $article_type
 * @property boolean $autocomplete
 * @property-read \Wepa\Core\Models\SeoTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wepa\Core\Models\SeoTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Seo listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Seo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Seo translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Seo whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Seo withTranslation()
 * @mixin \Eloquent
 */
class Seo extends Model implements TranslatableContract
{
	use HasFactory;
	use Translatable;
	use TranslationsTrait;
	
	
	public $timestamps = false;
	public array $translatedAttributes = [
		'keyword',
		'title',
		'slug',
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
	protected array $attrsArray = [];
	protected $casts = [
		'route_params' => 'array',
		'request_params' => 'array',
		'robots' => 'array',
	];
	protected $fillable = [
		'controller',
		'package',
		'alias',
		'action',
		'route_params',
		'request_params',
		'canonical',
		'robots',
		'page_type',
		'article_type',
		'image',
		'facebook_image',
		'twitter_image',
	];
	protected $table = 'core_seo';
	
	/**
	 * @return $this
	 */
	public function attrsToArray(array|string $attrs = []): static
	{
		if(is_array($attrs)) {
			$this->attrsArray = array_merge($this->attrsArray, $attrs);
		} else {
			$this->attrsArray[] = $attrs;
		}
		
		return $this;
	}
	
	public function site() : Attribute
	{
		return Attribute::make(
			get: fn () => Site::first()->only(['company', 'email', 'phone', 'mobile', 'address', 'latitude', 'longitude']),
		);
	}
	
	public function toArray()
	{
		$collection = collect(parent::toArray())
			->merge(['translations' => $this->getTranslationsArray()]);
		
		foreach($this->attrsArray as $attr) {
			$collection = $collection->merge([$attr => $this->{$attr}]);
		}
		
		return $collection;
	}
}
