@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Menu Kasir</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Produk Selector --}}
        <div class="col-span-2 bg-white shadow-md rounded-xl p-4">
            <h2 class="text-lg font-semibold mb-2">Cari Produk</h2>
            <input type="text" placeholder="Scan atau ketik nama produk..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" />

            <div class="mt-4">
                <ul>
                    <li class="flex items-center justify-between border-b py-2">
                        <span>Produk A</span>
                        <button class="bg-purple-600 text-white px-3 py-1 rounded-lg">Tambah</button>
                    </li>
                    <!-- List produk lainnya -->
                </ul>
            </div>
        </div>

        {{-- Keranjang --}}
        <div class="bg-white shadow-md rounded-xl p-4">
            <h2 class="text-lg font-semibold mb-2">Keranjang</h2>
            <ul class="space-y-2">
                <li class="flex justify-between">
                    <span>Produk A x2</span>
                    <span>Rp 40.000</span>
                </li>
                <!-- Item lainnya -->
            </ul>

            <div class="mt-4 border-t pt-2">
                <div class="flex justify-between font-semibold">
                    <span>Total</span>
                    <span>Rp 100.000</span>
                </div>

                <button class="mt-4 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection