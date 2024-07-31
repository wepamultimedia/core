<?php

namespace Wepa\Core\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Wepa\Core\Http\Traits\Backend\PositionModelTrait;

/**
 * Wepa\Core\Models\Menu
 *
 * @property int $id
 * @property string $label
 * @property string $icon
 * @property string $route
 * @property string $active
 * @property string $can
 * @property string $package
 * @property string $app
 * @property array $children
 * @property int $position
 * @property int $parent_id
 * @property-read \Wepa\Core\Models\MenuTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wepa\Core\Models\MenuTranslation[] $translations
 * @property-read int|null $translations_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Menu listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu withTranslation()
 *
 * @mixin Eloquent
 */
class Menu extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use PositionModelTrait;

    public array $children;

    public $timestamps = false;

    public array $translatedAttributes = ['label'];

    public $translationForeignKey = 'menu_id';

    protected $fillable = [
        'route',
        'parent_id',
        'icon',
        'active',
        'can',
        'package',
        'app',
        'position',
    ];

    protected $table = 'core_menu';
    public static string $cacheTag = 'core_menu';

    public static function loadPackageItems(string $package): void
    {
        Menu::where(['package' => $package])->delete();

        $menu = new Menu();

        $menu->addItems(config($package.'.frontend_menu', []),
            $package,
            'frontend');

        $menu->addItems(config($package.'.backend_menu', []),
            $package,
            'backend');

        cache()->forget(self::$cacheTag);
    }

    public function addItems(array $items,
                             string $package,
                             string $app,
                             int $parentId = null): void
    {
        if ($items) {
            $formatedItems = $this->formatItems($items);
            foreach ($formatedItems as $item) {
                /* @var $menu Menu */
                $newItem = array_merge(
                    $item, ['package' => $package, 'app' => $app]
                );

                unset($newItem['children']);
                $newItem['position'] = self::nextPosition(['parent_id' => $parentId]);

                $menu = Menu::create($newItem);

                if (Arr::exists($item, 'position')) {
                    $menu->updatePosition($item['position'],
                        ['parent_id' => $parentId]);
                }

                if ($parentId) {
                    $menu->parent_id = $parentId;
                    $menu->save();
                }

                if (Arr::exists($item, 'children')) {
                    $this->addItems($item['children'],
                        $package,
                        $app,
                        $menu->id);
                }
            }
        }
    }

    /**
     * Le damos formato al array configurado en el archivos de configuración del modulo
     */
    public function formatItems($items): array
    {
        $formatedItems = [];

        foreach ($items as $item) {
            $formatedItem = ['route' => $item['route']];
            if (Arr::exists($item, 'icon')) {
                $formatedItem = array_merge(
                    $formatedItem, ['icon' => $item['icon']]
                );
            }
            if (Arr::exists($item, 'active')) {
                $formatedItem = array_merge(
                    $formatedItem, ['active' => $item['active']]
                );
            }
            if (Arr::exists($item, 'can')) {
                $formatedItem = array_merge(
                    $formatedItem, ['can' => $item['can']]
                );
            }
            if (Arr::exists($item, 'position')) {
                $formatedItem = array_merge(
                    $formatedItem, ['position' => $item['position']]
                );
            }

            if (Arr::exists($item, 'label')) {
                if (Str::contains($item['label'], '|')) {
                    $translations = explode('|', $item['label']);
                    foreach ($translations as $translation) {
                        if (Str::contains($item['label'], ':')) {
                            [$locale, $label] = explode(':', $translation);
                            $formatedItem[$locale] = ['label' => $label];
                        }
                    }
                }
            }

            // Todo else
            if (Arr::exists($item, 'children')) {
                $formatedItem['children'] = $item['children'];
            }

            $formatedItems[] = $formatedItem;
        }

        return $formatedItems;
    }

    public static function removePackageItems(string $package): void
    {
        self::where(['package' => $package])->delete();

        cache()->forget(self::$cacheTag);
    }
}
