@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h2>Product Catalog</h2>
    </div>

    @can('view-catalog')
    <div class="row">
        @forelse ($products as $product)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm">
                <a href="{{ route('products.show', $product->id) }}">
                    <img src="{{ asset('storage/' . $product->image) }}"
                        class="card-img-top"
                        alt="{{ $product->name }}"
                        style="height: 200px; object-fit: cover;">
                </a>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>

                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <!-- Stock -->
                            <span>
                                <strong>Stock:</strong> {{ $product->stock }}
                            </span>

                            <!-- Harga -->
                            <span>
                                <strong>Rp</strong> {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>

                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            @can('add-cart')
                            <button type="submit"
                                class="btn btn-sm btn-outline-primary w-100"
                                title="Add to cart">
                                <i class="fas fa-shopping-cart"></i> Add to cart 
                            </button>
                            @endcan
                        </form>

                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p>There is no product</p>
        </div>
        @endforelse
    </div>
    @endcan
</div>
@endsection