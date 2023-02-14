<?php

namespace Wepa\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Wepa\Core\Models\MenuTranslation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation query()
 *
 * @mixin Eloquent
 */
class MenuTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['label'];

    protected $table = 'core_menu_translations';
}
