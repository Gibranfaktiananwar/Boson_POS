<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/feather-icons/dist/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/theme.min.js') }}"></script>

<script src="{{ asset('assets/libs/feather-icons/dist/feather.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof feather !== "undefined") {
            feather.replace(); // Render ulang ikon Feather
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let navMasterData = document.getElementById("navMasterData");
        let toggleMasterData = document.querySelector("[data-bs-target='#navMasterData']");

        if (navMasterData && toggleMasterData) {
            // Ambil status dropdown dari localStorage
            let isOpen = localStorage.getItem("navMasterDataOpen") === "true";

            if (isOpen) {
                navMasterData.classList.add("show");
                toggleMasterData.setAttribute("aria-expanded", "true");
            } else {
                navMasterData.classList.remove("show");
                toggleMasterData.setAttribute("aria-expanded", "false");
            }

            // Event listener untuk menyimpan status saat diklik
            toggleMasterData.addEventListener("click", function () {
                let isExpanded = navMasterData.classList.contains("show");
                localStorage.setItem("navMasterDataOpen", !isExpanded);
            });
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function () {
            // Cek apakah password dalam mode terlihat
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Ganti ikon
            } else {
                passwordInput.type = "password";
                togglePassword.innerHTML = '<i class="fas fa-eye"></i>'; // Ganti ikon
            }
        });
    });
</script>
