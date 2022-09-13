<?php

namespace Wepa\Core\Models\Backend;


use Illuminate\Database\Eloquent\Model;


class PermissionTranslation extends Model
{
	public $timestamps = false;
	protected $fillable = ['description'];
	protected $table = 'permissions_translations';
}
