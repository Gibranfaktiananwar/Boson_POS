@extends('layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
        <h2>Category List</h2>
        @can('add-category')
        <a href="{{ route('category.create') }}" class="btn btn-primary">Add Category</a>
        @endcan
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            @can('view-category')
            <thead>
                <tr>
                    <th>No</th>
                    <th>Category Code</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->code }}</td>
                    <td>{{ $category->name }}</td>
                    <td class="text-wrap" style="max-width: 300px; white-space: normal; word-break: break-word;">
                        {{ $category->description }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            @can('edit-category')
                            <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endcan
                            @can('delete-category')
                            <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No category found.</td>
                </tr>
                @endforelse
            </tbody>
            @endcan
        </table>
    </div>
</div>

@endsection