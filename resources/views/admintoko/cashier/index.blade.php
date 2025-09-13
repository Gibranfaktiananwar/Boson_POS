@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-4 mb-4">Shopping Cart</h2>
        @if(count($cart) > 0)
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="">
                    <tr>
                        <th></th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th width="150">Quantity</th>
                        <th>Subtotal</th>
                        <th width="250"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $item['image']) }}"
                                alt="{{ $item['name'] }}"
                                class="rounded"
                                style="height:60px; width:60px; object-fit:cover;">
                        </td>
                        <td>{{ $item['name'] }}</td>
                        <td>Rp {{ number_format($item['price'],0,',','.') }}</td>
                        <td>
                            <form action="{{ route('cart.update', $id) }}"
                                method="POST"
                                class="d-flex align-items-center mb-0">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="number"
                                        name="quantity"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        max="{{ $item['stock'] }}"
                                        class="form-control"
                                        style="max-width: 70px;">
                                    <button class="btn btn-primary btn-sm">OK</button>
                                </div>
                            </form>
                            <small class="text-muted">
                                In stock: {{ $item['stock'] }}
                            </small>
                        </td>
                        <td>Rp {{ number_format($item['price'] * $item['quantity'],0,',','.') }}</td>
                        <td class="align-middle">
                            <a href="{{ route('cart.remove', $id) }}"
                                class="btn btn-outline-danger btn-sm">
                                Delete
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <h5>
                Total: <span class="text-danger">
                    Rp {{ number_format($total,0,',','.') }}
                </span>
            </h5>
            <a href="{{ route('cart.checkout') }}"
                class="btn btn-success btn-lg">
                Checkout
            </a>
        </div>
    </div>
    @else
    <p>Your cart is empty. <a href="{{ route('products.index') }}">
            Continue shopping
        </a></p>
    @endif
</div>
@endsection