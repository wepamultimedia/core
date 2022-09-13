<?php

namespace Wepa\Core\Models\Backend;


use Eloquent;
use Illuminate\Database\Eloquent\Model;



/**
 * Wepa\Core\Models\Backend\MenuTranslation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuTranslation query()
 * @mixin Eloquent
 */
class MenuTranslation extends Model
{
	public $timestamps = false;
	protected $fillable = ['label'];
	protected $table = 'core_backend_menu_translations';
}
