<?php

namespace Wepa\Core\Http\Controllers\Backend;


use Illuminate\Http\Request;
use Wepa\Core\Models\File;


class FolderController extends InertiaController
{
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'string|required'
		]);
		
		File::create(array_merge($request->all(), ['type_id' => 1]));
	}
}
