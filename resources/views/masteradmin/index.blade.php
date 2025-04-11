@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-12 mt-6">
            <div class="card h-100">
                <div class="card-header bg-white py-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Data User</h4>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#userModal" class="text-primary">
                        <i data-feather="plus-circle" class="fs-3"></i>
                    </a>

                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap">
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
                            <tr>
                                <td class="align-middle">{{ $user->name }}</td>
                                <td class="align-middle">{{ $user->email }}</td>
                                <td class="align-middle">{{ $user->roles->pluck('name')->implode(', ') ?: 'N/A' }}</td>
                                <td class="align-middle">
                                    <a href="#" class="text-warning me-2 edit-user"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $user->roles->pluck('name')->implode(', ') ?: 'N/A' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#userModal">
                                        <i data-feather="edit" class="fs-4"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 border-0"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i data-feather="trash-2" class="fs-4"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add / Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="masteradmin">masteradmin</option>
                            <option value="admintoko">admintoko</option>
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
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const userModal = document.getElementById("userModal");
        const userForm = document.getElementById("userForm");
        const methodField = document.getElementById("methodField");
        const nameInput = document.getElementById("name");
        const emailInput = document.getElementById("email");
        const roleInput = document.getElementById("role");
        const passwordInput = document.getElementById("password");

        document.querySelectorAll(".edit-user").forEach(button => {
            button.addEventListener("click", function() {
                // Ambil data dari tombol edit
                const id = this.getAttribute("data-id");
                const name = this.getAttribute("data-name");
                const email = this.getAttribute("data-email");
                const role = this.getAttribute("data-role");

                // Isi form dengan data user
                nameInput.value = name;
                emailInput.value = email;
                roleInput.value = role;
                passwordInput.value = ""; // Kosongkan password

                // Ubah method menjadi PUT untuk update user
                methodField.value = "PUT";
                userForm.action = `/users/${id}`;
            });
        });

        // Reset form saat modal ditutup
        userModal.addEventListener("hidden.bs.modal", function() {
            userForm.reset();
            userForm.action = "{{ route('users.store') }}"; // Kembali ke tambah user
            methodField.value = "POST"; // Kembalikan method ke POST
        });
    });
</script>



@endsection