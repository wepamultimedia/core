<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Response;
use Wepa\Core\Http\Requests\Backend\PermissionFormRequest;
use Wepa\Core\Http\Requests\Backend\RoleFormRequest;
use Wepa\Core\Models\Permission;
use Wepa\Core\Models\Role;

class PermissionController extends InertiaController
{
    public string $packageName = 'core';

    /**
     * @param  Role  $role
     */
    public function destroy(Permission $permission): Redirector|Application|RedirectResponse
    {
        $permission->delete();

        return redirect(route('admin.permissions.index'));
    }

    public function edit(Permission $permission): Response
    {
        $translations = $permission->getTranslationsArray();

        return $this->render('Vendor/Core/Backend/Permission/Edit',
            'backend/permission',
            compact(['translations', 'permission']));
    }

    public function index(Request $request): Response
    {
        $permissions = Permission::when($request->search,
            function ($query, $search) {
                $query->where('name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('name')
            ->paginate();

        return $this->render('Vendor/Core/Backend/Permission/Index',
            'backend/permission',
            compact(['permissions']));
    }

    public function store(PermissionFormRequest $request): Redirector|RedirectResponse|Application
    {
        Permission::create(array_merge($request->all(),
            $request->translations,
            ['guard_name' => 'web']));

        return redirect(route('admin.permissions.index'));
    }

    public function create(): Response
    {
        return $this->render('Vendor/Core/Backend/Permission/Create',
            'backend/permission');
    }

    /**
     * @param  RoleFormRequest  $request
     * @param  Role  $role
     */
    public function update(PermissionFormRequest $request,
                           Permission $permission): Redirector|RedirectResponse|Application
    {
        $permission->update(array_merge($request->all(),
            $request->translations));

        return redirect(route('admin.permissions.index'));
    }
}
