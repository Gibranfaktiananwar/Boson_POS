<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        return view('role.index', [
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
        $role->delete();
        return back()->with('success', 'Role deleted.');
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
}