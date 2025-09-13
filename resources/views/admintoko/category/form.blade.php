@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>{{ isset($category) ? 'Edit' : 'Tambah' }} Kategori</h2>

    <form action="{{ isset($category) ? route('category.update', $category) : route('category.store') }}" method="POST">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label fw-semibold">Category Code</label>
            <input type="text" name="code" class="form-control border-primary-subtle" placeholder="e.g. 001" value="{{ old('code', $category->code ?? '') }}" required pattern="\d+">
            <small class="text-muted">* Input number only</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Category Name</label>
            <input type="text" name="name" class="form-control border-primary-subtle" placeholder="Enter category name" value="{{ old('name', $category->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control border-primary-subtle" rows="3" placeholder="Enter description">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button class="btn btn-success">
                Save Category
            </button>
        </div>
    </form>
</div>

@endsection
