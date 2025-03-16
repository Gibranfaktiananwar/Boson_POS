@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Scan Barcode untuk Redeem Rewards</h4>
        </div>
        <div class="card-body">
            <!-- Input scan barcode -->
            <div class="mb-3">
                <label class="form-label fw-bold">Masukkan Serial Number</label>
                <input type="text" id="serialInput" class="form-control" placeholder="Masukkan atau scan barcode...">
            </div>

            <!-- Tombol scan -->
            <button onclick="checkSerial()" class="btn btn-success w-100">Scan Barcode</button>
        </div>
    </div>

    <!-- Modal hasil scan -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hasil Scan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="resultMessage" class="fs-5"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkSerial() {
        let serial = document.getElementById('serialInput').value;
        let validSerials = ["ABC123", "XYZ789", "TEST456"]; // Dummy serial numbers
        
        let resultMessage = document.getElementById('resultMessage');
        if (validSerials.includes(serial)) {
            resultMessage.textContent = "✅ Serial number valid! Selamat, Anda mendapatkan rewards!";
            resultMessage.classList.add("text-success");
        } else {
            resultMessage.textContent = "❌ Serial number tidak valid. Silakan coba lagi.";
            resultMessage.classList.add("text-danger");
        }
        
        // Tampilkan modal
        let modal = new bootstrap.Modal(document.getElementById('resultModal'));
        modal.show();
    }
</script>
@endsection
