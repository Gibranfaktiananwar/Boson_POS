@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow-md rounded-xl mt-3">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cek Ketersediaan Barang</h2>

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
        return data;
    }

    async function checkSerial() {
        const serial = document.getElementById("serialInput").value;
        const msgEl = document.getElementById("message");

        // Step 1: Generate token dulu
        const tokenResponse = await generateToken();

        if (!tokenResponse.success) {
            msgEl.innerHTML = `
                <div class="p-4 bg-red-100 text-red-800 rounded-lg">
                    ${tokenResponse.message || "Gagal generate token"}
                </div>
            `;
            return;
        }

        msgEl.innerHTML = `<div class="p-4 rounded-lg bg-yellow-100 text-yellow-800">📡 Mengecek SN...</div>`;

        // Step 2: Lanjut cek serial
        const response = await fetch("{{ route('serial.check') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify({ sn: serial }),
        });

        const data = await response.json();

        if (data.success) {
            const detail = data.data.content;
            msgEl.innerHTML = `
                <div class="p-4 bg-white/90 text-gray-900 rounded-lg shadow space-y-1">
                    <p><strong>🔢 SN:</strong> ${detail.sn}</p>
                    <p><strong>📦 Produk:</strong> ${detail.product_name}</p>
                    <p><strong>📍 Lokasi:</strong> ${detail.location}</p>
                    <p><strong>📌 Status:</strong> 
                        <span class="${detail.status === 'enable' ? 'text-green-600' : 'text-red-600'} font-semibold">
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
