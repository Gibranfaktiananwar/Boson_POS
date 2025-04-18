@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Sales</h1>

    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded-xl p-4">
            <h2 class="text-gray-500 text-sm">Hari Ini</h2>
            <p class="text-2xl font-bold text-purple-600">Rp 2.500.000</p>
        </div>
        <div class="bg-white shadow rounded-xl p-4">
            <h2 class="text-gray-500 text-sm">Bulan Ini</h2>
            <p class="text-2xl font-bold text-blue-600">Rp 43.000.000</p>
        </div>
        <div class="bg-white shadow rounded-xl p-4">
            <h2 class="text-gray-500 text-sm">Total Penjualan</h2>
            <p class="text-2xl font-bold text-green-600">Rp 1.230.000.000</p>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-4">
        <div class="flex items-center gap-2">
            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500" />
            <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500" />
        </div>
        <input type="text" placeholder="Cari transaksi..." class="mt-2 md:mt-0 border border-gray-300 rounded-lg px-3 py-2 text-sm w-full md:w-64 focus:ring-2 focus:ring-purple-500" />
    </div>

    {{-- Tabel Penjualan --}}
    <div class="bg-white shadow rounded-xl overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">#ID</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Kasir</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                
                <tr>
                    <td class="px-4 py-3 font-medium text-gray-800">#</td>
                    <td class="px-4 py-3">jj</td>
                    <td class="px-4 py-3">jj</td>
                    <td class="px-4 py-3 font-semibold text-green-600">Rp </td>
                    <td class="px-4 py-3">
                        
                    </td>
                    <td class="px-4 py-3">
                        <button class="text-blue-600 hover:underline text-sm">Detail</button>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>
@endsection
