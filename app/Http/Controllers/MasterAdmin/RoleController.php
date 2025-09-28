<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        return view('masteradmin.role.index', [
            'role' => Role::with('permissions')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create(['name' => $data['name']]);
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return back()->with('success', 'Role created.');
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => "required|string|min:3|unique:roles,name,{$role->id}",
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'masteradmin') {
            return back()->with('error', 'Role masteradmin tidak boleh dihapus.');
        }

        DB::transaction(function () use ($role) {
            // Ambil semua user yang memiliki role ini
            $users = User::role($role->name)->get();

            // Hapus user terkait
            foreach ($users as $user) {
                $user->delete(); // gunakan ->forceDelete() jika tidak pakai soft deletes
            }

            // Terakhir, hapus rolenya
            $role->delete();
        });

        return back()->with('success', "Role '{$role->name}' beserta seluruh user yang terkait telah dihapus.");
    }


    // Tambah permission baru dari UI (opsional)
    public function storePermission(Request $request)
    {
        $data = $request->validate([
            'permission' => 'required|string|min:3|unique:permissions,name',
        ]);
        Permission::create(['name' => $data['permission']]);
        return back()->with('success', 'Permission created.');
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'permission' => 'required|string|min:3|unique:permissions,name,' . $permission->id,
        ]);
        $permission->update(['name' => $data['permission']]);
        return back()->with('success', 'Permission updated.');
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Permission deleted.');
    }


    // app/Http/Controllers/Masteradmin/RoleController.php

    public function permissions(Role $role)
    {
        // kembalikan daftar permission yg dimiliki role (array of names)
        return response()->json([
            'role' => $role->only(['id', 'name']),
            'permissions' => $role->permissions()->pluck('name')
        ]);
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        return back()->with('success', "Permissions untuk role '{$role->name}' berhasil diperbarui.");
    }
}
