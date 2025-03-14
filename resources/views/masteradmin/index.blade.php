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
                                            <a href="#" class="text-warning me-2" data-bs-toggle="modal"
                                                data-bs-target="#userModal" data-id="{{ $user->id }}">
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
                <form id="userForm" method="POST" action="{{ route('users.store') }}">
                    @csrf
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
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
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
        document.addEventListener('DOMContentLoaded', function() {
            var userModal = document.getElementById('userModal');
            userModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var userId = button.getAttribute('data-id');
                var modalTitle = userModal.querySelector('.modal-title');
                var userForm = userModal.querySelector('#userForm');

                if (userId) {
                    modalTitle.textContent = 'Edit User';
                    userForm.action = `/users/${userId}`;
                    userForm.insertAdjacentHTML('beforeend',
                        '<input type="hidden" name="_method" value="PUT">');
                } else {
                    modalTitle.textContent = 'Add User';
                    userForm.action = "{{ route('users.store') }}";
                }
            });
        });
    </script>
@endsection
