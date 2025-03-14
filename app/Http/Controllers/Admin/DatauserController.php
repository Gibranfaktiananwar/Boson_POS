<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatauserController extends Controller
{
    public function index()
    {
        // display roles that are not masteradmin
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'masteradmin');
        })->latest()->paginate(10);
        $roles = Role::all();
        return view('masteradmin.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'User has been successfully added.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]);

        return back()->with('success', 'User has been successfully updated.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User has been successfully deleted.');
    }
}
