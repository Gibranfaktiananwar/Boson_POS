{{-- resources/views/role/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 mt-6">
            <div class="card h-100">
                <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Manajemen Role</h4>
                    <div class="d-flex gap-2">
                        {{-- Tambah Permission cepat --}}
                        <form action="{{ route('permissions.store') }}" method="POST" class="d-flex">
                            @csrf
                            <input type="text" name="permission" class="form-control form-control-sm" placeholder="Permission baru" required>
                            <button class="btn btn-sm btn-outline-secondary ms-2">Tambah Permission</button>
                        </form>

                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#roleModal"
                            onclick="openCreateRole()">+ Role</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table text-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($role as $role)
                                <tr>
                                    <td class="align-middle">{{ $role->name }}</td>
                                    <td class="align-middle">
                                        @forelse($role->permissions as $perm)
                                            <span class="badge bg-light text-dark border me-1 mb-1">{{ $perm->name }}</span>
                                        @empty
                                            <span class="text-muted">—</span>
                                        @endforelse
                                    </td>
                                    <td class="align-middle text-end">
                                        <button class="btn btn-sm btn-warning me-2"
                                            onclick="openEditRole({{ $role->id }}, '{{ $role->name }}', @json($role->permissions->pluck('name')))">
                                            <i data-feather="edit"></i>
                                        </button>
                                        <form action="{{ route('role.destroy', $role) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus role ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" {{ $role->name === 'masteradmin' ? 'disabled' : '' }}>
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">Belum ada role.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    @if (session('success'))
                        <div class="alert alert-success mb-0">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mb-0">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger mb-0">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Create/Edit Role --}}
<div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="roleForm" class="modal-content" method="POST">
        @csrf
        <input type="hidden" name="_method" id="roleFormMethod" value="POST">
        <div class="modal-header">
            <h5 class="modal-title" id="roleModalTitle">Tambah Role</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Nama Role</label>
                <input type="text" class="form-control" name="name" id="roleName" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Permissions</label>
                <select class="form-control" name="permissions[]" id="rolePermissions" multiple size="8">
                    @foreach($permissions as $p)
                        <option value="{{ $p->name }}">{{ $p->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-1">Tahan Ctrl (Windows) / Cmd (Mac) untuk multi-select.</small>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-primary" type="submit">Simpan</button>
        </div>
    </form>
  </div>
</div>

<script>
function openCreateRole() {
    document.getElementById('roleModalTitle').innerText = 'Tambah Role';
    const form = document.getElementById('roleForm');
    form.action = "{{ route('role.store') }}";
    document.getElementById('roleFormMethod').value = 'POST';
    document.getElementById('roleName').value = '';
    [...document.getElementById('rolePermissions').options].forEach(o => o.selected = false);
}

function openEditRole(id, name, selectedPerms) {
    const modal = new bootstrap.Modal(document.getElementById('roleModal'));
    document.getElementById('roleModalTitle').innerText = 'Edit Role';
    const form = document.getElementById('roleForm');
    form.action = "{{ url('role') }}/" + id;
    document.getElementById('roleFormMethod').value = 'PUT';
    document.getElementById('roleName').value = name;

    const sel = document.getElementById('rolePermissions');
    [...sel.options].forEach(o => o.selected = selectedPerms.includes(o.value));
    modal.show();
}
</script>
@endsection
