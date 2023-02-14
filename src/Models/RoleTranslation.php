<?php

namespace Wepa\Core\Models;

use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['description'];

    protected $table = 'roles_translations';
}
