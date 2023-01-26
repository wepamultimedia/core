<?php

namespace Wepa\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;


class SeoController extends Controller
{
	/**
	 * @param string $text
	 * @param string|null $locale
	 *
	 * @return string
	 */
	public function slug(string $text, string $locale = null): string
	{
		$language = $locale?? app()->getLocale();
		
		return Str::slug($text, '-', $language);
	}
}
