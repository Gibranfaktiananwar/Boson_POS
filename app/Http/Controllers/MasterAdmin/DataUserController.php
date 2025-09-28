<?php

namespace App\Http\Controllers\MasterAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DataUserController extends Controller
{

    public function index()
    {
        // Ambil user beserta rolenya
        $users = User::with('roles')->latest()->paginate(10);

        // Jika ingin sembunyikan role 'masteradmin' dari pilihan:
        // $roles = Role::where('name', '!=', 'masteradmin')->get();
        $roles = Role::all();

        return view('masteradmin.user.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        // Validasi create user
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|string|exists:roles,name',
            'password' => 'required|string|min:6',
        ]);

        // Simpan user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // === Penempatan assignRole setelah user tersimpan ===
        $user->assignRole($request->role);

        return back()->with('success', 'User has been successfully added.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi update; password opsional
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'required|string|exists:roles,name',
            'password' => 'nullable|string|min:6', // opsional saat edit
        ]);

        // Update field dasar
        $updateData = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Jika password diisi, update juga
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // === Penempatan syncRoles SETELAH $user->update(...) ===
        // Ini akan mengganti role-user sesuai pilihan saat edit
        if ($request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        return back()->with('success', 'User has been successfully updated.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // (Opsional) Cegah hapus diri sendiri / masteradmin tertentu
        // if (auth()->id() === $user->id) {
        //     return back()->with('error', 'You cannot delete your own account.');
        // }

        $user->delete();
        return back()->with('success', 'User has been successfully deleted.');
    }
}
