<?php

namespace Wepa\Core\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Wepa\Core\Models\Menu;


class MenuController extends Controller
{
	/**
	 * @param string $app
	 *
	 * @return array
	 */
	public function getMenu(Request $request, string $app): array
	{
		return self::buildMenu($app);
	}
	
	/**
	 * @param string $app
	 * @param null $items
	 * @param null $parentId
	 *
	 * @return array
	 */
	public static function buildMenu(string $app,
	                                 array  $items = null,
	                                 int    $parentId = null): array
	{
		if(!$items) {
			$items = Menu::orderBy('parent_id')
				->orderBy('position')
				->get()->toArray();
		}
		
		$menu = [];
		/* @var self $item */
		foreach($items as $item) {
			if($item['parent_id'] === $parentId) {
				if(Auth::user()->can($item['can'])) {
					$itemMenu = [
						'id' => $item['id'],
						'label' => $item['label'],
						'route' => $item['route'],
						'active' => $item['active'],
						'can' => $item['can'],
						'position' => $item['position'],
					];
					
					if($item['icon']) {
						$itemMenu = array_merge($itemMenu,
							['icon' => $item['icon']]);
					}
					
					$subItems = self::buildMenu($app, $items, $item['id']);
					
					$itemMenu = count($subItems)
						? array_merge($itemMenu, ['submenu' => $subItems])
						: $itemMenu;
					
					$menu[] = $itemMenu;
				}
			}
		}
		
		return $menu;
	}
}
