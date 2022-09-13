<?php

namespace Wepa\Core\Http\Controllers\Backend;


use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Inertia\Response;
use Wepa\Core\Http\Controllers\Mixed\InertiaController;
use Wepa\Core\Http\Requests\Backend\UserCreateRequest;
use Wepa\Core\Http\Requests\Backend\UserEditRequest;
use Wepa\Core\Http\Requests\RegisterRequest;
use Wepa\Core\Models\Backend\Role;


class UserController extends InertiaController
{
	public string $packageName = 'core';
	
	/**
	 * @param User $user
	 *
	 * @return RedirectResponse
	 */
	public function destroy(User $user): RedirectResponse
	{
		$user->delete();
		
		return redirect()->route('admin.users.index');
	}
	
	/**
	 * @param User $user
	 *
	 * @return Response
	 */
	public function edit(User $user): Response
	{
		$roles = Role::all();
		$selectedRoles = $user->getRoleNames()->all();
		
		return $this->render('Vendor/Core/Backend/User/Edit',
			'admin.user',
			compact(['user', 'roles', 'selectedRoles']));
	}
	
	/**
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request): Response
	{
		$users = User::when($request->search, function($query, $search) {
			$query->where('name', 'LIKE', '%' . $search . '%')
				->orWhere('email', 'LIKE', '%' . $search . '%');
		})
			->with('roles')
			->orderBy('name')
			->paginate(10);
		
		return $this->render('Vendor/Core/Backend/User/Index', 'admin.user', [
			'users' => $users,
		]);
	}
	
	/**
	 * @param UserCreateRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function store(UserCreateRequest $request): RedirectResponse
	{
		$user = [
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => Hash::make($request->password),
		];
		$user = User::create($user);
		$user->syncRoles($request->selectedRoles);
		
		return redirect()->route('admin.users.index');
	}
	
	/**
	 * @return Response
	 */
	public function create(): Response
	{
		$roles = Role::all();
		
		return $this->render('Vendor/Core/Backend/User/Create', 'admin.user', ['roles' => $roles]);
	}
	
	/**
	 * @param UserEditRequest $request
	 * @param User $user
	 *
	 * @return RedirectResponse
	 */
	public function update(UserEditRequest $request, User $user): RedirectResponse
	{
		$user->update($request->all());
		$user->syncRoles($request->selectedRoles);
		
		return redirect()->route('admin.users.index');
	}
}
