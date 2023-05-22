<?php

namespace Wepa\Core\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Wepa\Core\Http\Traits\Backend\SeoModelTrait;


/**
 * Wepa\Core\Models\Site
 *
 * @property int $id
 * @property int $seo_id
 * @property int $maintenance
 * @property string|null $company
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $facebook_url
 * @property string|null $twitter_url
 * @property string|null $youtube_url
 * @property string|null $skype_url
 * @property string|null $linkedin_url
 * @property string|null $instagram_url
 * @property string|null $vimeo_url
 * @property string|null $twitch_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $icon
 * @property string|null $logo
 * @property string|null $logo_invert
 *
 * @method static Builder|Site newModelQuery()
 * @method static Builder|Site newQuery()
 * @method static Builder|Site query()
 * @method static Builder|Site whereAddress($value)
 * @method static Builder|Site whereCompany($value)
 * @method static Builder|Site whereCreatedAt($value)
 * @method static Builder|Site whereEmail($value)
 * @method static Builder|Site whereFacebookUrl($value)
 * @method static Builder|Site whereId($value)
 * @method static Builder|Site whereInstagramUrl($value)
 * @method static Builder|Site whereLatitude($value)
 * @method static Builder|Site whereLinkedinUrl($value)
 * @method static Builder|Site whereLongitude($value)
 * @method static Builder|Site whereMaintenance($value)
 * @method static Builder|Site whereMobile($value)
 * @method static Builder|Site wherePhone($value)
 * @method static Builder|Site whereSkypeUrl($value)
 * @method static Builder|Site whereTwitchUrl($value)
 * @method static Builder|Site whereTwitterUrl($value)
 * @method static Builder|Site whereUpdatedAt($value)
 * @method static Builder|Site whereVimeoUrl($value)
 * @method static Builder|Site whereYoutubeUrl($value)
 *
 * @mixin Eloquent
 */
class Site extends Model
{
    
    protected array $attrsArray = [];
    
    protected $fillable = [
        'seo_id',
        'maintenance',
        'company',
        'email',
        'phone',
        'mobile',
        'address',
        'latitude',
        'longitude',
        'facebook',
        'twitter',
        'youtube',
        'youtube',
        'skype',
        'linkedin',
        'instagram',
        'vimeo',
        'twitch',
        'whatsapp',
        'icon',
        'logo',
        'logo_invert',
    ];
    
    protected $table = 'core_site';
    
    /**
     * @return $this
     */
    public function attrsToArray(array|string $attrs = []): static
    {
        if (is_array($attrs)) {
            $this->attrsArray = array_merge($this->attrsArray, $attrs);
        } else {
            $this->attrsArray[] = $attrs;
        }
        
        return $this;
    }
    
    public function seo(): HasOne
    {
        return $this->hasOne(Seo::class, 'id', 'seo_id');
    }
    
    public function toArray(): array
    {
        $collection = collect(parent::toArray());
        
        foreach ($this->attrsArray as $attr) {
            $collection = $collection->merge([$attr => $this->{$attr}]);
        }
        
        return $collection->toArray();
    }
}
