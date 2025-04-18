@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Riwayat Transaksi</h1>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">#ID</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">#</td>
                        <td class="px-4 py-3">k</td>
                        <td class="px-4 py-3">k</td>
                        <td class="px-4 py-3 font-semibold text-green-600">Rp </td>
                        <td class="px-4 py-3">
                            
                        </td>
                        <td class="px-4 py-3">
                            <a href="" class="text-blue-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                
            </tbody>
        </table>
    </div>
</div>
@endsection
