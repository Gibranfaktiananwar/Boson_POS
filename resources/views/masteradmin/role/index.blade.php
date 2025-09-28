@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-12 mt-6">
            <div class="card h-100">
                <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Role & Permission</h4>
                    <div class="d-flex gap-2">
                        <button id="openAddPermission" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#permissionModal">
                            Permission
                        </button>
                        <button id="openAddRole" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleModal">
                            Role
                        </button>
                    </div>
                </div>

                {{-- Flash messages --}}
                @if (session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- ====== HANYA 1 TABEL: Role | Permissions | Actions ====== --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:28%">Role</th>
                                <th>Permissions</th>
                                <th class="text-end" style="width:160px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($role as $r)
                            @php
                            $perms = $r->permissions->pluck('name');
                            $totalPerms = $permissions->count(); // total permission yg ada di sistem
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $r->name }}</td>
                                <td class="text-muted">
                                    @if ($perms->count() === $totalPerms && $totalPerms > 0)
                                    <span class="badge bg-success">All permissions are granted</span>
                                    @else
                                    {{ $perms->isEmpty() ? '—' : $perms->implode(', ') }}
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button
                                        class="btn btn-sm btn-outline-primary manage-perm"
                                        data-role-id="{{ $r->id }}"
                                        data-role-name="{{ $r->name }}">
                                        Manage Permission
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ================== MODALS ================== --}}

{{-- MODAL CRUD ROLE (add/edit/delete) --}}
<div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- Add Role --}}
                <form class="d-flex gap-2 mb-3" method="POST" action="{{ route('role.store') }}">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Type a new role here" required>
                    <button class="btn btn-primary">Add Role</button>
                </form>

                {{-- List Role (Edit/Delete di sini, bukan di tabel utama) --}}
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Role</th>
                                <th class="text-end" style="width:220px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($role as $r)
                            <tr>
                                <td class="fw-semibold">{{ $r->name }}</td>
                                <td class="text-end">
                                    @if($r->name !== 'masteradmin')
                                    <form method="POST" action="{{ route('role.update', $r->id) }}" class="d-inline align-middle">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $r->name }}" class="form-control d-inline-block w-auto" required>
                                        <button type="submit" class="btn btn-sm btn-warning">Edit</button>
                                    </form>
                                    <form method="POST" action="{{ route('role.destroy', $r->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('User associated with {{ $r->name }} will also be deleted')">Delete</button>
                                    </form>
                                    @else
                                    <span class="badge bg-secondary">Locked</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL CRUD PERMISSION (add/edit/delete) --}}
<div class="modal fade" id="permissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- Add Permission --}}
                <form class="d-flex gap-2 mb-3" method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    <input type="text" name="permission" class="form-control" placeholder="Type a new permission here" required>
                    <button class="btn btn-outline-primary">Add Permission</button>
                </form>

                {{-- List Permission (edit/Delete di sini) --}}
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Permission</th>
                                <th class="text-end" style="width:220px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $p)
                            <tr>
                                <td class="fw-semibold">{{ $p->name }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('permissions.update', $p->id) }}" class="d-inline align-middle">
                                        @csrf @method('PUT')
                                        <input type="text" name="permission" value="{{ $p->name }}" class="form-control d-inline-block w-auto" required>
                                        <button type="submit" class="btn btn-sm btn-warning">Edit</button>
                                    </form>
                                    <form method="POST" action="{{ route('permissions.destroy', $p->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete permission {{ $p->name }}?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL ASSIGN/REVOKE PERMISSIONS KE ROLE --}}
<div class="modal fade" id="permModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="permAssignForm" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Manage permission <span id="permRoleName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    @foreach ($permissions as $p)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="form-check">
                            <input class="form-check-input perm-check" type="checkbox"
                                id="perm-{{ Str::slug($p->name) }}"
                                name="permissions[]"
                                value="{{ $p->name }}">
                            <label class="form-check-label" for="perm-{{ Str::slug($p->name) }}">
                                {{ $p->name }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Assign/Revoke permissions ke role
        const permAssignModal = new bootstrap.Modal(document.getElementById('permModal'));
        const permAssignForm = document.getElementById('permAssignForm');
        const permRoleNameEl = document.getElementById('permRoleName');
        const permChecks = document.querySelectorAll('.perm-check');

        document.querySelectorAll('.manage-perm').forEach(btn => {
            btn.addEventListener('click', async () => {
                const roleId = btn.dataset.roleId;
                const roleName = btn.dataset.roleName;

                // reset centang
                permChecks.forEach(ch => ch.checked = false);

                // load permission milik role
                const res = await fetch(`{{ url('role') }}/${roleId}/permissions`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();

                permRoleNameEl.textContent = roleName;
                permAssignForm.action = `{{ url('role') }}/${roleId}/permissions-sync`;

                const owned = new Set(data.permissions);
                permChecks.forEach(ch => {
                    if (owned.has(ch.value)) ch.checked = true;
                });

                permAssignModal.show();
            });
        });

        // Set tombol header supaya modal CRUD mulai di mode 'add'
        document.getElementById('openAddRole')?.addEventListener('click', () => {
            // nothing special; form add sudah default di modal Role
        });
        document.getElementById('openAddPermission')?.addEventListener('click', () => {
            // nothing special; form add sudah default di modal Permission
        });
    });
</script>
@endsection