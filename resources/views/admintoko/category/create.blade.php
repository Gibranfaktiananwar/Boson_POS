@extends('layouts.app')

@section('content')
<div class="container mt-5 p-4 rounded shadow-sm bg-white" style="max-width: 600px;">
    <h4 class="mb-4 text-primary">🗂️ Create New Category</h4>
    <form action="{{ route('category.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">📦 Category Code</label>
            <input type="text" name="code" class="form-control border-primary-subtle" placeholder="e.g. 001" required pattern="\d+">
            <small class="text-muted">* Input number only</small>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">📝 Category Name</label>
            <input type="text" name="name" class="form-control border-primary-subtle" placeholder="Enter category name" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">🗒️ Description</label>
            <textarea name="description" class="form-control border-primary-subtle" rows="3" placeholder="Enter description"></textarea>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">
                ⬅️ Cancel
            </a>
            <button class="btn btn-primary">
                💾 Save Category
            </button>
        </div>
    </form>
</div>

@endsection
