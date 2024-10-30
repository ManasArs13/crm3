<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{

    public function users($role = null)
    {
        if ($role) {
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })->orderBy('id', 'DESC')->get();
        } else {
            $users = User::with('roles')->orderBy('id', 'DESC')->get();
        }
        $entity = 'users';

        return view('users.index', compact("entity", "users"));
    }

    public function create(){
        $entity = 'add_user';
        return view('users.create', compact('entity'));
    }

    public function store(UserUpdateRequest $request)
    {
        $user = User::Create([
            'name' => $request->name,
            'email' => $request->login,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);
        return redirect()->route('users.all');
    }

    public function edit($id){
        $entity = 'user';
        $user = User::with('roles')->find($id);
        return view('users/edit', compact('entity', 'user'));
    }

    public function update(UserUpdateRequest $request)
    {
        $user = User::find($request->managment);
        $password = $request->password ?? $user->password;
        $user->Update([
            'name' => $request->name,
            'email' => $request->login,
            'password' => $password,
        ]);
        $user->syncRoles($request->role);
        return redirect()->route('users.all');
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->managment);
        $user->delete();
        return redirect()->route('users.all');
    }

    public function permission()
    {

        $roles = Role::with('permissions')->orderBy('id', 'asc')->get();
        $permissions = Permission::OrderBy('id', 'asc')->get();

        $entity = 'permission_roles';

        return view('users.permission.index', compact("entity", "roles", "permissions"));
    }

}
