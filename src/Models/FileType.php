<?php

namespace Wepa\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Wepa\Core\Models\FileType
 *
 * @property int $id
 * @property string $name
 * @property string $extension
 * @property string|null $icon
 * @property string|null $mime
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FileType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileType query()
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FileType whereName($value)
 *
 * @mixin \Eloquent
 */
class FileType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'core_files_types';

    protected $fillable = ['name', 'extension', 'icon', 'mime'];
}
