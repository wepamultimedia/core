<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Inertia\Response;


class FileManagerController extends InertiaController
{
	public string $packageName = 'core';
	
	public function index(int $parentId = null): Response
	{
		return $this->render('Core/Backend/Files/Index', 'backend/files');
	}
}
