<?php
/**
 * Core package config file
 */
return [
	'can_login'    => true,
	'can_register' => true,
	'theme'       => '', // resources/views/themes/{theme}
	'backend_menu' => [
		[
			'label'    => 'en:Dashboard|es:Escritorio',
			'icon'     => 'presentation-chart-bar',
			'route'    => 'admin.dashboard',
			'active'   => 'admin.dashboard',
			'can'      => 'admin.auth',
			'position' => 1,
		],
		[
			'label'    => 'en:System|es:Sistema',
			'icon'     => 'user-group',
			'route'    => '#',
			'can'      => 'admin.users.index',
			'position' => 2,
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
			'label'    => 'en:Translations|es:Traducciones',
			'icon'     => 'flag',
			'route'    => 'admin.translations',
			'active'   => 'admin.translations*',
			'can'      => 'admin.translations',
			'position' => 3,
		],
	],
	'locales'     => [
		[
			'code' => 'es',
			'name' => 'Español',
		],
		[
			'code' => 'en',
			'name' => 'English',
		],
		[
			'code' => 'fr',
			'name' => 'Française',
		],
	],
];
