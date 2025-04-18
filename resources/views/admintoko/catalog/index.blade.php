@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">📦 Katalog Produk</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <img class="w-full h-40 object-cover">
                <div class="p-4">
                    <h2 class="font-semibold text-lg"> product name </h2>
                    <p class="text-sm text-gray-500 mb-2"> category</p>
                    <p class="font-bold text-purple-600">Rp </p>
                    <button class="mt-2 w-full bg-purple-600 text-black py-1 rounded-lg hover:bg-purple-700 text-sm">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
    </div>
</div>
@endsection
