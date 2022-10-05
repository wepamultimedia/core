<?php

namespace Wepa\Core\Database\seeders;

use Illuminate\Database\Seeder;
use Wepa\Core\Models\Backend\Menu;


class AdminMenuSedder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    Menu::add([
		    [
			    'label'    => 'en:Dashboard|es:Escritorio',
			    'icon'     => 'presentation-chart-bar',
			    'route'    => 'admin.dashboard',
			    'active' => 'admin.dashboard',
			    'can'      => 'admin.auth',
		    ],
		    [
			    'label'    => 'en:System|es:Sistema',
			    'icon'     => 'user-group',
			    'route'    => '#',
			    'can'      => 'admin.users.index',
			    'children' => [
				    [
					    'label'  => 'en:Users|es:Usuarios',
					    'route'  => 'admin.users.index',
					    'active' => 'admin.users*',
					    'can'    => 'admin.users.index',
				    ],
				    [
					    'label'  => 'en:Roles|es:Roles',
					    'route'  => 'admin.roles.index',
					    'active' => 'admin.roles*',
					    'can'    => 'admin.roles.index',
				    ],
				    [
					    'label'  => 'en:Permissions|es:Permisos',
					    'route'  => 'admin.permissions.index',
					    'active' => 'admin.permissions*',
					    'can'    => 'admin.permissions.index',
				    ],
			    ],
		    ],
		    [
			    'label'  => 'en:Translations|es:Traducciones',
			    'icon'   => 'flag',
			    'route'  => 'admin.translations',
			    'active' => 'admin.translations*',
			    'can'    => 'admin.translations',
		    ],
	    ]);
    }
}
