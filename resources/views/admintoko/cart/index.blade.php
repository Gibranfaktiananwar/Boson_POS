@extends('layouts.app')

@section('content')

<style>
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
/* Firefox */
.qty-input[type="number"] {
    -moz-appearance: textfield;
    appearance: textfield; /* fallback modern */
}
</style>

<div class="container">
    <h2 class="mt-4 mb-4">Shopping Cart</h2>
    @can('view-cart')
    @if($items->isNotEmpty())
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th></th>
                        <th>Product Name</th>
                        <th>Price per unit</th>
                        <th width="180" class="text-center">Quantity</th>
                        <th>Subtotal</th>
                        <th width="250"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    @php
                        $product   = $item->product;
                        $price     = $product->price ?? 0;
                        $quantity  = $item->quantity ?? 0;
                        $subtotal  = $price * $quantity;
                    @endphp
                    <tr>
                        <td>
                            @if(!empty($product->image))
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="rounded"
                                     style="height:60px; width:60px; object-fit:cover;">
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>Rp {{ number_format($price, 0, ',', '.') }}</td>
                        @can('edit-cart')
                        <td class="text-center">
                            <form action="{{ route('cart.update', $product->id) }}"
                                  method="POST"
                                  class="d-inline qty-form">
                                @csrf
                                <div class="input-group input-group-sm mx-auto" style="max-width: 130px;">
                                    <button type="button"
                                            class="btn btn-outline-secondary qty-btn"
                                            data-step="-1">−</button>

                                    <input type="number"
                                           name="quantity"
                                           value="{{ $quantity }}"
                                           min="1"
                                           max="{{ $product->stock }}"
                                           class="form-control text-center qty-input">

                                    <button type="button"
                                            class="btn btn-outline-secondary qty-btn"
                                            data-step="1">+</button>
                                </div>
                            </form>
                            <small class="text-muted d-block mt-1 text-center">
                                In stock: {{ $product->stock }}
                            </small>
                        </td>
                        @endcan
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        <td class="align-middle">
                            @can('delete-cart')
                            <a href="{{ route('cart.remove', $product->id) }}"
                               class="btn btn-outline-danger btn-sm">
                                Delete
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <h5>
                Total: <span class="text-danger">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </h5>
            <a href="{{ route('cart.checkout') }}"
               class="btn btn-success btn-lg">
               Checkout
            </a>
        </div>
    </div>
    @endcan
    @else
        <p>Your cart is empty. <a href="{{ route('products.index') }}">Continue shopping</a></p>
    @endif
</div>

{{-- Script untuk tombol plus/minus --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // klik plus/minus
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const step = parseInt(this.dataset.step, 10);
            const form = this.closest('.qty-form');
            const input = form.querySelector('.qty-input');

            const min = parseInt(input.getAttribute('min') || '1', 10);
            const max = parseInt(input.getAttribute('max') || '999999', 10);
            let val = parseInt(input.value || '0', 10);

            val = isNaN(val) ? min : val;
            val = Math.min(max, Math.max(min, val + step));

            if (val !== parseInt(input.value, 10)) {
                input.value = val;
                form.submit(); // auto kirim ke cart.update
            }
        });
    });

    // submit otomatis saat user ketik manual
    document.querySelectorAll('.qty-input').forEach(inp => {
        inp.addEventListener('change', function () {
            const min = parseInt(this.getAttribute('min') || '1', 10);
            const max = parseInt(this.getAttribute('max') || '999999', 10);
            let val = parseInt(this.value || '0', 10);
            val = isNaN(val) ? min : val;
            val = Math.min(max, Math.max(min, val));
            this.value = val;
            this.closest('.qty-form').submit();
        });
    });
});
</script>
@endsection
