@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-12 mt-6">
            <div class="card h-100">
                <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Data User</h4>

                    {{-- TOMBOL ADD USER hanya tampil jika punya permission add-user --}}
                    @can('add-user')
                    <a href="#"
                       id="openCreateUser"
                       data-bs-toggle="modal"
                       data-bs-target="#userModal"
                       class="text-primary">
                        <i data-feather="plus-circle" class="fs-3"></i>
                    </a>
                    @endcan

                </div>

                <div class="table-responsive">
                    <table class="table text-nowrap">
                        @can('view-user')
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            @php
                                $firstRole = optional($user->roles->first())->name;
                            @endphp
                            <tr>
                                <td class="align-middle">{{ $user->name }}</td>
                                <td class="align-middle">{{ $user->email }}</td>
                                <td class="align-middle">{{ $user->roles->pluck('name')->implode(', ') ?: 'N/A' }}</td>
                                <td class="align-middle">
                                    {{-- TOMBOL EDIT hanya tampil jika punya permission edit-user --}}
                                    @can('edit-user')
                                    <a href="#"
                                       class="text-warning me-2 edit-user"
                                       data-id="{{ $user->id }}"
                                       data-name="{{ $user->name }}"
                                       data-email="{{ $user->email }}"
                                       data-role="{{ $firstRole ?? '' }}"
                                       data-bs-toggle="modal"
                                       data-bs-target="#userModal">
                                        <i data-feather="edit" class="fs-4"></i>
                                    </a>
                                    @endcan

                                    {{-- TOMBOL DELETE hanya tampil jika punya permission delete-user --}}
                                    @can('delete-user')
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 border-0"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i data-feather="trash-2" class="fs-4"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @endcan
                    </table>
                </div>

                <div class="card-footer">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add/Edit User -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="userForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required />
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            @foreach ($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password">
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <small class="text-muted" id="passwordHelpText">
                            Must be filled in when adding a new user
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save changes</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const userModal     = document.getElementById("userModal");
    const userForm      = document.getElementById("userForm");
    const methodField   = document.getElementById("methodField");
    const nameInput     = document.getElementById("name");
    const emailInput    = document.getElementById("email");
    const roleInput     = document.getElementById("role");
    const passwordInp   = document.getElementById("password");
    const modalTitle    = document.getElementById("userModalLabel");
    const openCreateBtn = document.getElementById("openCreateUser");
    const passwordHelp  = document.getElementById("passwordHelpText");
    const saveBtn       = document.getElementById("saveBtn");

    function setAddMode() {
        modalTitle.textContent = "Add User";
        passwordHelp.textContent = "Must be filled in when adding a new user";

        // tombol jadi "Submit"
        saveBtn.textContent = "Submit";

        passwordInp.required = true;
        saveBtn.disabled = passwordInp.value.trim().length === 0;
    }

    function setEditMode() {
        modalTitle.textContent = "Edit User";
        passwordHelp.textContent = "Optional, you can change the password.";

        // tombol tetap "Save changes"
        saveBtn.textContent = "Save changes";

        passwordInp.required = false;
        saveBtn.disabled = false;
    }

    passwordInp.addEventListener("input", function() {
        if (passwordInp.required) {
            saveBtn.disabled = passwordInp.value.trim().length === 0;
        }
    });

    if (openCreateBtn) {
        openCreateBtn.addEventListener("click", function () {
            userForm.reset();
            methodField.value = "POST";
            userForm.action   = "{{ route('users.store') }}";

            if (roleInput.options.length > 0) roleInput.selectedIndex = 0;
            passwordInp.value = "";

            setAddMode();
        });
    }

    document.querySelectorAll(".edit-user").forEach(btn => {
        btn.addEventListener("click", function () {
            const id    = this.getAttribute("data-id");
            const name  = this.getAttribute("data-name");
            const email = this.getAttribute("data-email");
            const role  = this.getAttribute("data-role") || "";

            nameInput.value  = name;
            emailInput.value = email;
            passwordInp.value = "";

            if (role) {
                roleInput.value = role;
            } else {
                if (roleInput.options.length > 0) roleInput.selectedIndex = 0;
            }

            methodField.value = "PUT";
            userForm.action   = "{{ url('/users') }}/" + id;

            setEditMode();
        });
    });

    userModal.addEventListener("hidden.bs.modal", function () {
        userForm.reset();
        methodField.value = "POST";
        userForm.action   = "{{ route('users.store') }}";
        if (roleInput.options.length > 0) roleInput.selectedIndex = 0;

        setAddMode();
    });

    const toggle = document.getElementById("togglePassword");
    if (toggle) {
        toggle.addEventListener("click", function () {
            const type = passwordInp.getAttribute("type") === "password" ? "text" : "password";
            passwordInp.setAttribute("type", type);
        });
    }

    setAddMode();
});
</script>
@endsection
