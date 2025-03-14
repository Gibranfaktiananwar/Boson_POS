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
