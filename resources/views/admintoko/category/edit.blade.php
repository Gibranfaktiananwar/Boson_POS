@extends('layouts.app')

@section('content')
<div class="container mt-5 p-4 rounded shadow-sm bg-white" style="max-width: 600px;">
    <h4 class="mb-4 text-primary">✏️ Edit Category</h4>
    <form action="{{ route('category.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">📝 Category Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control border-primary-subtle" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">🗒️ Description</label>
            <textarea name="description" class="form-control border-primary-subtle" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">
                ⬅️ Cancel
            </a>
            <button class="btn btn-success">
                ✅ Update Category
            </button>
        </div>
    </form>
</div>

@endsection
