<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Response;
use Wepa\Core\Http\Requests\Backend\RoleFormRequest;
use Wepa\Core\Models\Permission;
use Wepa\Core\Models\Role;

class RoleController extends InertiaController
{
    public string $packageName = 'core';

    public function destroy(Role $role): Redirector|Application|RedirectResponse
    {
        $role->delete();

        return redirect(route('admin.roles.index'));
    }

    public function edit(Role $role): Response
    {
        $permissions = Permission::all();
        $selectedPermissions = $role->getPermissionNames()->all();
        $translations = $role->getTranslationsArray();

        return $this->render('Core/Backend/Role/Edit',
            'backend/role',
            compact([
                'role',
                'permissions',
                'selectedPermissions',
                'translations',
            ]));
    }

    public function index(Request $request): Response
    {
        $roles = Role::when($request->search, function ($query, $search) {
            $query->where('name', 'LIKE', '%'.$search.'%');
        })
            ->orderBy('name')
            ->paginate();

        return $this->render('Core/Backend/Role/Index',
            'backend/role',
            compact(['roles']));
    }

    public function store(RoleFormRequest $request): Redirector|RedirectResponse|Application
    {
        /* @var Role $role */
        $role = Role::create(array_merge($request->all(), $request->translations,
            ['guard_name' => 'web']));
        $role->syncPermissions($request->selectedPermissions);

        return redirect(route('admin.roles.index'));
    }

    public function create(): Response
    {
        $permissions = Permission::all();

        return $this->render('Core/Backend/Role/Create',
            'backend/role',
            compact(['permissions']));
    }

    public function update(RoleFormRequest $request,
                           Role $role): Redirector|RedirectResponse|Application
    {
        $role->update(array_merge($request->all(), $request->translations));
        $role->syncPermissions($request->selectedPermissions);

        return redirect(route('admin.roles.index'));
    }
}
