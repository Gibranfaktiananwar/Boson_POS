@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <h2>{{ isset($product) ? 'Edit' : 'Add' }} Product</h2>

    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Category</label>

            @if($categories->isEmpty())
            <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
                <span>Tidak ada kategori tersedia. Silakan buat kategori terlebih dahulu.</span>

                @if (Route::has('category.create'))
                <a href="{{ route('category.create') }}" class="btn btn-sm btn-primary">Buat Kategori</a>
                @endif
            </div>
            @endif

            <select name="code" class="form-control" {{ $categories->isEmpty() ? 'disabled' : 'required' }}>
                <option value="">{{ $categories->isEmpty() ? '— Tidak ada kategori —' : '-- Pilih Kategori --' }}</option>
                @foreach ($categories as $c)
                <option value="{{ $c->code }}" {{ old('code', $product->code ?? '') == $c->code ? 'selected' : '' }}>
                    {{ $c->code }}{{ isset($c->name) ? ' - '.$c->name : '' }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Product Image</label>
            <input type="file" name="image" class="form-control">
            @if(isset($product) && $product->image)
            <small class="d-block mt-2">Current Image:</small>
            <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="img-thumbnail" width="150">
            @endif
        </div>


        <button class="btn btn-success">Save</button>
        <a href="{{ route('products.management') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection