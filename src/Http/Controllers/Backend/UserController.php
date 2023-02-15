<?php

namespace Wepa\Core\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;
use Wepa\Core\Http\Requests\Backend\UserCreateRequest;
use Wepa\Core\Http\Requests\Backend\UserEditRequest;
use Wepa\Core\Models\Role;

class UserController extends InertiaController
{
    public string $packageName = 'core';

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user): Response
    {
        $roles = Role::all();
        $selectedRoles = $user->getRoleNames()->all();

        return $this->render('Core/Backend/User/Edit',
            'backend/user',
            compact(['user', 'roles', 'selectedRoles']));
    }

    public function index(Request $request): Response
    {
        $users = User::when($request->search, function ($query, $search) {
            $query->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('email', 'LIKE', '%'.$search.'%');
        })
            ->with('roles')
            ->orderBy('name')
            ->paginate(3);

        return $this->render('Core/Backend/User/Index', 'backend/user', [
            'users' => $users,
        ]);
    }

    public function show(Request $request, User $user): Application|RedirectResponse|Redirector
    {
        return redirect(route('admin.user.profile'));
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        $user = User::create($user);
        $user->syncRoles($request->selectedRoles);

        return redirect()->route('admin.users.index');
    }

    public function create(): Response
    {
        $roles = Role::all();

        return $this->render('Core/Backend/User/Create',
            'backend/user',
            ['roles' => $roles]);
    }

    public function update(UserEditRequest $request,
                           User $user): RedirectResponse
    {
        $user->update($request->all());
        $user->syncRoles($request->selectedRoles);

        return redirect()->route('admin.users.index');
    }
}
