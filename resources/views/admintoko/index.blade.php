@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow-md rounded-xl mt-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cek Ketersediaan Barang</h2>

    <!-- Tombol Generate Token -->
    <div class="mb-6">
        <button onclick="generateToken()" class="px-4 py-2 bg-blue-600 text-black rounded-lg hover:bg-blue-700 transition">
            Generate Token
        </button>
    </div>

    <!-- Form Input SN -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mb-6">
        <input type="text" id="serialInput" placeholder="Masukkan Serial Number"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />

        <button onclick="checkSerial()" class="px-4 py-2 bg-green-600 text-black rounded-lg hover:bg-green-700 transition">
            Check SN
        </button>
    </div>

    <!-- Output Message -->
    <div id="message" class="text-sm mt-4"></div>
</div>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    async function generateToken() {
        const response = await fetch("{{ route('token.generate') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            }
        });

        const data = await response.json();
        const msgEl = document.getElementById("message");

        if (data.success) {
            msgEl.innerHTML = `
                <div class="p-4 bg-green-100 text-green-800 rounded-lg">
                    Token berhasil digenerate dan disimpan!
                </div>
            `;
        } else {
            msgEl.innerHTML = `
                <div class="p-4 bg-red-100 text-red-800 rounded-lg">
                    ${data.message || "Gagal generate token"}
                </div>
            `;
        }
    }

    async function checkSerial() {
        const serial = document.getElementById("serialInput").value;

        const response = await fetch("{{ route('serial.check') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify({ sn: serial }),
        });

        const data = await response.json();
        const msgEl = document.getElementById("message");

        if (data.success) {
            const detail = data.data.content;
            msgEl.innerHTML = `
                <div class="p-4 bg-gray-100 rounded-lg shadow-sm space-y-1">
                    <p><strong>SN:</strong> ${detail.sn}</p>
                    <p><strong>Product Name:</strong> ${detail.product_name}</p>
                    <p><strong>Lokasi:</strong> ${detail.location}</p>
                    <p><strong>Status:</strong> 
                        <span class="${detail.status === 'enable' ? 'text-green-600' : 'text-red-600'}">
                            ${detail.status}
                        </span>
                    </p>
                </div>
            `;
        } else {
            msgEl.innerHTML = `
                <div class="p-4 bg-red-100 text-red-800 rounded-lg">
                    ${data.message || "Gagal cek serial"}
                </div>
            `;
        }
    }
</script>
@endsection
