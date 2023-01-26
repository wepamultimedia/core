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
		
		return $this->render('Core/Backend/User/Edit',
			'backend/user',
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
			->paginate(3);
		
		return $this->render('Core/Backend/User/Index', 'backend/user', [
			'users' => $users,
		]);
	}
	
	/**
	 * @param Request $request
	 * @param User $user
	 *
	 * @return Application|RedirectResponse|Redirector
	 */
	public function show(Request $request, User $user): Application|RedirectResponse|Redirector
	{
		return redirect(route('admin.user.profile'));
	}
	
	/**
	 * @param UserCreateRequest $request
	 *
	 * @return RedirectResponse
	 */
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
	
	/**
	 * @return Response
	 */
	public function create(): Response
	{
		$roles = Role::all();
		
		return $this->render('Core/Backend/User/Create',
			'backend/user',
			['roles' => $roles]);
	}
	
	/**
	 * @param UserEditRequest $request
	 * @param User $user
	 *
	 * @return RedirectResponse
	 */
	public function update(UserEditRequest $request,
	                       User            $user): RedirectResponse
	{
		$user->update($request->all());
		$user->syncRoles($request->selectedRoles);
		
		return redirect()->route('admin.users.index');
	}
}
