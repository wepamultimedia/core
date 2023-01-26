<?php

namespace Wepa\Core\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RoleTranslation extends Model
{
	public $timestamps = false;
	protected $fillable = ['description'];
	protected $table = 'roles_translations';
}
