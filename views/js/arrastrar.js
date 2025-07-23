document.addEventListener("DOMContentLoaded", () => {
    const palabras = document.querySelectorAll(".drag-palabra");
    const pistas = document.querySelectorAll(".drop-pista");

    let draggedId = null;
    let aciertos = 0;
    const totalParejas = palabras.length;

    palabras.forEach(palabra => {
        palabra.addEventListener("dragstart", (e) => {
            draggedId = palabra.dataset.id;
            palabra.style.opacity = "0.5";
            e.dataTransfer.setData("text/plain", draggedId);
        });

        palabra.addEventListener("dragend", () => {
            palabra.style.opacity = "1";
        });
    });

    pistas.forEach(pista => {
        pista.addEventListener("dragover", (e) => {
            e.preventDefault();
            pista.style.background = "#c8e6c9";  // indicación visual
        });

        pista.addEventListener("dragleave", () => {
            pista.style.background = "#e8f5e9"; // reset visual
        });

        pista.addEventListener("drop", (e) => {
            e.preventDefault();
            pista.style.background = "#e8f5e9";

            const idPalabra = e.dataTransfer.getData("text/plain");

            if (idPalabra === pista.dataset.id) {
                // Emparejado correctamente
                const palabraElem = document.querySelector(`.drag-palabra[data-id='${idPalabra}']`);
                palabraElem.style.background = "#28a745";
                palabraElem.style.color = "white";
                palabraElem.style.cursor = "default";
                palabraElem.setAttribute("draggable", "false");

                pista.style.border = "2px solid #28a745";
                pista.style.background = "#a5d6a7";

                aciertos++;

                // Opcional: desactivar palabra para no volver a arrastrar
                palabraElem.removeEventListener("dragstart", () => {});

                // Mostrar mensaje cuando termine
                if (aciertos === totalParejas) {
                    marcarJuegoComoCompletado(window.idJuegoActual);
                            // juego/game95100/3
                    Swal.fire({
                        title: "¡Felicidades!",
                        text: "Has completado el Desafío",
                        icon: "success",
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = SERVERURL + "juego/" + window.idJuegoActual + "/3";
                    });
                }
            } else {
                // Mal emparejado: opcional mostrar error
                alert("No coincide la palabra con esta pista. Intenta otra vez.");
            }
        });
    });
    function marcarJuegoComoCompletado(idJuego) {
        fetch(SERVERURL + "ajax/ajaxJuegoCompletado.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id_juego=${encodeURIComponent(idJuego)}`
        }).then(res => res.text()).then(console.log);
    }
});
