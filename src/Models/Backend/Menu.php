<?php

namespace Wepa\Core\Models\Backend;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


/**
 * Wepa\Core\Models\Backend\Menu
 *
 * @property int $id
 * @property string $label
 * @property string $icon
 * @property string $route
 * @property string $active
 * @property string $can
 *
 * @property int $parent_id
 * @property-read \Wepa\Core\Models\Backend\MenuTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wepa\Core\Models\Backend\MenuTranslation[] $translations
 * @property-read int|null $translations_count
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
 * @mixin Eloquent
 */
class Menu extends Model implements TranslatableContract
{
	use HasFactory;
	use Translatable;
	
	
	public $timestamps = false;
	public $translatedAttributes = ['label'];
	public $translationForeignKey = 'menu_id';
	protected $fillable = ['route', 'parent_id', 'icon', 'active', 'can'];
	protected $table = 'core_backend_menu';
	
	/**
	 * @param array $items
	 * @param int|null $parentId
	 *
	 * @return void
	 */
	public static function add(array $items, int $parentId = null): void
	{
		foreach($items as $item) {
			$data = ['route' => $item['route']];
			if(Arr::exists($item, 'icon')) {
				$data = array_merge($data, ['icon' => $item['icon']]);
			}
			if(Arr::exists($item, 'active')) {
				$data = array_merge($data, ['active' => $item['active']]);
			}
			if(Arr::exists($item, 'can')) {
				$data = array_merge($data, ['can' => $item['can']]);
			}
			if(Arr::exists($item, 'label')) {
				if(Str::contains($item['label'], '|')) {
					$translations = explode('|', $item['label']);
					foreach($translations as $translation) {
						if(Str::contains($item['label'], ':')) {
							[$locale, $label] = explode(':', $translation);
							$data[ $locale ] = ['label' => $label];
						}
					}
				}
			}
			
			/* @var $menu Menu */
			$menu = Menu::create($data);
			
			if($parentId) {
				$menu->parent_id = $parentId;
			}
			
			if(Arr::exists($item, 'children')) {
				self::add($item['children'], $menu->id);
			}
			
			$menu->save();
		}
	}
	
	/**
	 * @param $items
	 * @param $parentId
	 *
	 * @return array
	 */
	public static function menu($items = null, $parentId = null): array
	{
		if(!$items) {
			$items = Menu::get();
		}
		
		$menu = [];
		
		/* @var self $item */
		foreach($items as $item) {
			if($item->parent_id === $parentId) {
				if(Auth::user()->can($item->can)) {
					$itemMenu = [
						'label'  => $item->label,
						'route'  => $item->route,
						'active' => $item->active,
						'can'    => $item->can,
					];
					if($item->icon) {
						$itemMenu = array_merge($itemMenu, ['icon' => $item->icon]);
					}
					$subItems = Menu::menu($items, $item->id);
					$menu[] = count($subItems) ? array_merge($itemMenu, ['submenu' => $subItems]) : $itemMenu;
				}
			}
		}
		
		return $menu;
	}
}
