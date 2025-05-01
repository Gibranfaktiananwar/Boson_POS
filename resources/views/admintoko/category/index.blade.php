@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
        <h4>Category List</h4>
        <a href="{{ route('category.create') }}" class="btn btn-primary">+ Add Category</a>
    </div>

    <!-- Bungkus table dengan .table-responsive supaya scroll secara horisontal jika perlu -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Category Code</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->code }}</td>
                    <td>{{ $category->name }}</td>
                    <!-- Tambahkan class dan style untuk mem-break kata dan batasi lebar sel -->
                    <td class="text-wrap" style="max-width: 300px; white-space: normal; word-break: break-word;">
                        {{ $category->description }}
                    </td>
                    <td>
                        <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No category found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection