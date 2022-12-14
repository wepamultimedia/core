<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Response;
use Wepa\Core\Http\Controllers\Mixed\InertiaController;
use Wepa\Core\Http\Requests\Backend\PermissionFormRequest;
use Wepa\Core\Http\Requests\Backend\RoleFormRequest;
use Wepa\Core\Models\Backend\Permission;
use Wepa\Core\Models\Backend\Role;


class PermissionController extends InertiaController
{
	public string $packageName = 'core';
	
	/**
	 * @param Permission $permission
	 *
	 * @return Response
	 */
	public function edit(Permission $permission): Response
	{
		$translations = $permission->getTranslationsArray();
		
		return $this->render('Core/Backend/Permission/Edit',
			'backend/permission',
			compact(['translations', 'permission']));
	}
	
	/**
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request): Response
	{
		$permissions = Permission::when($request->search, function($query, $search) {
			$query->where('name', 'LIKE', '%' . $search . '%');
		})
			->orderBy('name')
			->paginate();
		
		return $this->render('Core/Backend/Permission/Index', 'backend/permission', compact(['permissions']));
	}
	
	/**
	 * @param PermissionFormRequest $request
	 *
	 * @return Redirector|RedirectResponse|Application
	 */
	public function store(PermissionFormRequest $request): Redirector|RedirectResponse|Application
	{
		Permission::create(array_merge($request->all(), ['guard_name' => 'web']));
		
		return redirect(route('admin.permissions.index'));
	}
	
	/**
	 * @return Response
	 */
	public function create(): Response
	{
		return $this->render('Core/Backend/Permission/Create', 'backend/permission');
	}
	
	/**
	 * @param RoleFormRequest $request
	 * @param Role $role
	 *
	 * @return Application|RedirectResponse|Redirector
	 */
	public function update(PermissionFormRequest $request, Permission $permission): Redirector|RedirectResponse|Application
	{
		$permission->update($request->all());
		
		return redirect(route('admin.permissions.index'));
	}
	
	/**
	 * @param Role $role
	 *
	 * @return Redirector|Application|RedirectResponse
	 */
	public function destroy(Permission $permission): Redirector|Application|RedirectResponse
	{
		$permission->delete();
		
		return redirect(route('admin.permissions.index'));
	}
}
