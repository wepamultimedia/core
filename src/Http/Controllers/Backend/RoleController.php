<?php

namespace Wepa\Core\Http\Controllers\Backend;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Response;
use Wepa\Core\Http\Controllers\Mixed\InertiaController;
use Wepa\Core\Http\Requests\Backend\RoleFormRequest;
use Wepa\Core\Models\Backend\Permission;
use Wepa\Core\Models\Backend\Role;


class RoleController extends InertiaController
{
	public string $packageName = 'core';
	
	/**
	 * @param Role $role
	 *
	 * @return Redirector|Application|RedirectResponse
	 */
	public function destroy(Role $role): Redirector|Application|RedirectResponse
	{
		$role->delete();
		
		return redirect(route('admin.roles.index'));
	}
	
	/**
	 * @param Role $role
	 *
	 * @return Response
	 */
	public function edit(Role $role): Response
	{
		$permissions = Permission::all();
		$selectedPermissions = $role->getPermissionNames()->all();
		$translations = $role->getTranslationsArray();
		
		return $this->render('backend/role/Edit',
			'backend/role',
			compact([
				'role',
				'permissions',
				'selectedPermissions',
				'translations',
			]));
	}
	
	/**
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request): Response
	{
		$roles = Role::when($request->search, function($query, $search) {
			$query->where('name', 'LIKE', '%' . $search . '%');
		})
			->orderBy('name')
			->paginate();
		
		return $this->render('backend/role/Index', 'backend/role', compact(['roles']));
	}
	
	/**
	 * @param RoleFormRequest $request
	 *
	 * @return Application|Redirector|RedirectResponse
	 */
	public function store(RoleFormRequest $request): Redirector|RedirectResponse|Application
	{
		/* @var Role $role */
		$role = Role::create(array_merge($request->all(), ['guard_name' => 'web']));
		$role->syncPermissions($request->selectedPermissions);
		
		return redirect(route('admin.roles.index'));
	}
	
	/**
	 * @return Response
	 */
	public function create(): Response
	{
		$permissions = Permission::all();
		
		return $this->render('@core/backend/role/Create',
			'backend/role',
			compact(['permissions']));
	}
	
	/**
	 * @param RoleFormRequest $request
	 * @param Role $role
	 *
	 * @return Application|RedirectResponse|Redirector
	 */
	public function update(RoleFormRequest $request, Role $role): Redirector|RedirectResponse|Application
	{
		$role->update($request->all());
		$role->syncPermissions($request->selectedPermissions);
		
		return redirect(route('admin.roles.index'));
	}
}
