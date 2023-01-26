<?php

namespace Wepa\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Wepa\Core\Database\Factories\FileMangerFactory;

/**
 * Wepa\Core\Models\File
 *
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 *
 * @mixin \Eloquent
 *
 * @property int $id
 * @property int $parent_id
 * @property int $type_id
 * @property string $name
 * @property string $url
 * @property string $file
 * @property string $alt_name
 * @property string $description
 * @property FileType $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder|File whereAltName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUrl($value)
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'type_id',
        'name',
        'url',
        'file',
        'alt_name',
        'description',
    ];

    protected $table = 'core_files';

    /**
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(FileType::class, 'id', 'type_id');
    }

    /**
     * @return FileMangerFactory
     */
    protected static function newFactory(): FileMangerFactory
    {
        return new FileMangerFactory();
    }
}
