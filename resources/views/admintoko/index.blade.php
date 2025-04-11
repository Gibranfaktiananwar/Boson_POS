@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header text-center" style="background-color: #624bff;">
            <h4 class="mb-0 text-white">Check Serial Number</h4>
        </div>

        <div class="card-body">
            <!-- Form 1: Masukkan Username Baru -->
            <div id="formUsername">
                <div class="mb-3">
                    <label class="form-label fw-bold">Masukkan Username Baru</label>
                    <input type="text" id="usernameInput" class="form-control" placeholder="Masukkan username...">
                </div>
                <button onclick="submitUsername()" class="btn btn-success w-100">Submit</button>
            </div>

            <!-- Form 2: Masukkan UID & Secret (Tersembunyi Awalnya) -->
            <div id="formToken" style="display: none;">
                <div class="mb-3">
                    <label class="form-label fw-bold">UID</label>
                    <input type="text" id="uidInput" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Secret</label>
                    <input type="text" id="secretInput" class="form-control" readonly>
                </div>
                <button onclick="requestToken()" class="btn btn-primary w-100">Dapatkan Token</button>
            </div>

            <!-- Form 3: Masukkan Serial Number (Tersembunyi Awalnya) -->
            <div id="formSerial" style="display: none;">
                <div class="mb-3">
                    <label class="form-label fw-bold">Masukkan atau Scan Serial Number</label>
                    <input type="text" id="serialInput" class="form-control" placeholder="Masukkan atau scan barcode...">
                </div>

                <button onclick="checkSerial()" class="btn btn-info w-100">Cek Serial</button>
            </div>
        </div>
    </div>

    <!-- Modal Hasil -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Hasil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="resultMessage" class="fs-5 mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        let globalToken = "";

        async function submitUsername() {
            let username = document.getElementById('usernameInput').value;
            if (!username) return alert("Username harus diisi!");

            let formData = new URLSearchParams();
            formData.append("username", username);
            let encodedBody = formData.toString();

            try {
                let response = await fetch("https://grobx.sinarmaju.co.id/api/check-inventory/generate-credential", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: encodedBody
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                let data = await response.json();

                document.getElementById("uidInput").value = data.content.uid;
                document.getElementById("secretInput").value = data.content.secret;

                document.getElementById("formUsername").style.display = "none";
                document.getElementById("formToken").style.display = "block";

                showMessage(data.message || "Berhasil", "text-success");

            } catch (error) {
                console.error("Error:", error);
                showMessage("Terjadi kesalahan", "text-danger");
            }
        }

        async function requestToken() {
            let uuid = document.getElementById("uidInput").value;
            let secret = document.getElementById("secretInput").value;

            let response = await fetch("https://grobx.sinarmaju.co.id/api/check-inventory/get-token", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `uuid=${encodeURIComponent(uuid)}&secret=${encodeURIComponent(secret)}`
            });

            let data = await response.json();

            if (data.success) {
                console.log('ppppp');
                console.log('info', data.content);
                globalToken = data.content[":token"];
                sessionStorage.setItem("inventoryToken", globalToken);

                console.log("Token didapat:", globalToken);
                // globalToken = content.token;

                // gasss coba cok


                // globalToken = data.content?.token;
                console.log('tokenie', globalToken);

                sessionStorage.setItem('inventoryToken', data.content.token);

                document.getElementById("formToken").style.display = "none";
                document.getElementById("formSerial").style.display = "block";
                showMessage("Token berhasil dibuat!", "text-success");
            } else {
                showMessage(data.message, "text-danger");
            }
        }

        async function checkSerial() {
            let serial = document.getElementById("serialInput").value;
            // const token = sessionStorage.getItem('inventoryToken'); // Ambil dari sessionStorage
            const token = globalToken || sessionStorage.getItem("inventoryToken");

            let response = await fetch("https://grobx.sinarmaju.co.id/api/check-inventory", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `sn=${encodeURIComponent(serial)}&token=${encodeURIComponent(token)}`
            });


            if (!token) {
                showMessage("Token belum tersedia. Silakan login terlebih dahulu.", "text-danger");
                return;
            }

            let data = await response.json();

            document.getElementById("inventoryToken").value = data.content.token;

            if (response.ok) {
                showMessage(
                    `Nomor SN: ${data.sn}\nStatus: ${data.status}\nLokasi: ${data.location}`,
                    "text-success"
                );
            } else {
                showMessage(data.message || "Gagal memeriksa serial number", "text-danger");
            }
        }


        function showMessage(message, className) {
            let resultMessage = document.getElementById("resultMessage");
            resultMessage.textContent = message;
            resultMessage.className = "fs-5 " + className;
            let modal = new bootstrap.Modal(document.getElementById("resultModal"));
            modal.show();
        }
    </script>

    @endsection