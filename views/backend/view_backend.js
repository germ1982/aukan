document.addEventListener("DOMContentLoaded", function () {
    // 1. Splash intro (Ocultar tras 3.5 segundos)
    setTimeout(() => {
        const splash = document.getElementById("intro-splash-overlay");
        if (splash) splash.classList.add("fade-out");
    }, 3500);

    // 2. Inicialización de Select2 para filtrar las tarjetas PHP en tiempo real
    if (typeof $ !== "undefined" && $.fn.select2) {
        const $select = $("#buscador-backend");
        const $btnReset = $("#btn-reset-search");

        $select.select2({
            placeholder: "🔍 Buscar por nombre o endpoint...",
            allowClear: true
        }).on("select2:select", function (e) {
            const idSeleccionado = e.params.data.id;
            filtrarTarjetas(idSeleccionado);
        }).on("select2:clear", function () {
            mostrarTodas();
        });

        $btnReset.on("click", function() {
            $select.val(null).trigger("change");
            mostrarTodas();
        });
    }

    function filtrarTarjetas(idTarget) {
        const tarjetas = document.querySelectorAll(".card-backend-item");
        const $btnReset = $("#btn-reset-search");

        tarjetas.forEach(card => {
            if (card.getAttribute("data-id") === idTarget) {
                card.style.display = "flex";
                card.classList.remove("card-out");
            } else {
                card.style.display = "none";
            }
        });

        if ($btnReset) $btnReset.show();
    }

    function mostrarTodas() {
        const tarjetas = document.querySelectorAll(".card-backend-item");
        const $btnReset = $("#btn-reset-search");

        tarjetas.forEach(card => {
            card.style.display = "flex";
            card.classList.remove("card-out");
        });

        if ($btnReset) $btnReset.hide();
    }
});