<?php
/**
 * Core package config file
 */
return [
	'canLogin'    => true,
	'canRegister' => true,
	'theme'       => 'rococo', // resources/views/themes/{theme}
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
