<?php
/**
 * Core package config file
 */
return [
    'can_login' => true,
    'can_register' => true,
    'google_api_key' => env('google_api_key', null),
    'max_filesize' => 8192, // KB
    'theme' => [
        'frontend' => '',
        'backend' => '',
    ], // resources/views/themes/{theme}
    'pagination' => [
        'filemanager' => 50,
    ],
    'routes' => [
        'force_locale_prefix' => true
    ],
    'default_locale' => 'es',
    'locales' => [
        [
            'code' => 'es',
            'iso' => 'es_ES',
            'flag' => 'ES',
            'name' => 'Español',
        ],
        //		[
        //			'code' => 'en',
        //			'iso'  => 'en_US',
        //			'flag'  => 'US',
        //			'name' => 'English',
        //		],
        //		[
        //			'code' => 'fr',
        //			'iso'  => 'fr_FR',
        //			'flag'  => 'FR',
        //			'name' => 'Française',
        //		],
    ],
    'backend_menu' => [
        [
            'label' => 'en:Dashboard|es:Escritorio',
            'icon' => 'presentation-chart-bar',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
            'can' => 'admin.auth',
            'position' => 1,
        ],
        [
            'label' => 'en:System|es:Sistema',
            'icon' => 'user-group',
            'route' => '#',
            'can' => 'admin.users.index',
            'position' => 2,
            'children' => [
                [
                    'label' => 'en:Users|es:Usuarios',
                    'route' => 'admin.users.index',
                    'active' => 'admin.users*',
                    'can' => 'admin.users.index',
                ],
                [
                    'label' => 'en:Roles|es:Roles',
                    'route' => 'admin.roles.index',
                    'active' => 'admin.roles*',
                    'can' => 'admin.roles.index',
                ],
                [
                    'label' => 'en:Permissions|es:Permisos',
                    'route' => 'admin.permissions.index',
                    'active' => 'admin.permissions*',
                    'can' => 'admin.permissions.index',
                ],
                [
                    'label' => 'en:Translations|es:Traducciones',
                    'route' => 'admin.translations.index',
                    'active' => 'admin.translations*',
                    'can' => 'admin.translations',
                ],
            ],
        ],
        [
            'label' => 'en:Website|es:Sitio web',
            'icon' => 'globe-alt',
            'route' => 'admin.seo.index',
            'active' => '',
            'can' => 'admin.seo',
            'position' => 3,
            'children' => [
                [
                    'label' => 'en:Setup|es:Configuración',
                    'route' => 'admin.site.edit',
                    'active' => 'admin.site*',
                    'can' => 'admin',
                ],
                [
                    'label' => 'en:SEO|es:SEO',
                    'route' => 'admin.seo.index',
                    'active' => 'admin.seo*',
                    'can' => 'admin.seo',
                ],
            ],
        ],
        [
            'label' => 'en:Files and Images|es:Archivos e imágenes',
            'icon' => 'film',
            'route' => 'admin.files.index',
            'active' => 'admin.files*',
            'can' => 'admin.auth',
            'position' => 4,
        ],
    ],
];
