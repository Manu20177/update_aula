
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("memoria-container");
    const cartas = window.cartasMemoria;

    if (!Array.isArray(cartas)) return;

    let primera = null;
    let bloqueo = false;

    // Cantidad total de parejas
    const totalParejas = cartas.length / 2;

    // Contador de parejas encontradas
    let parejasEncontradas = 0;

    cartas.forEach((carta, i) => {
        const div = document.createElement("div");
        div.classList.add("carta");
        div.dataset.index = i;
        div.dataset.id = carta.id;
        div.dataset.tipo = carta.tipo;
        div.dataset.contenido = carta.contenido;

        div.style.width = "150px";
        div.style.height = "80px";
        div.style.background = "#007bff";
        div.style.color = "white";
        div.style.border = "2px solid #fff";
        div.style.display = "flex";
        div.style.alignItems = "center";
        div.style.justifyContent = "center";
        div.style.fontWeight = "bold";
        div.style.cursor = "pointer";
        div.innerText = "???";

        div.addEventListener("click", () => {
            if (bloqueo || div.classList.contains("completado")) return;

            div.innerText = carta.contenido;
            div.style.background = "#ffc107";

            if (!primera) {
                primera = div;
            } else {
                bloqueo = true;
                setTimeout(() => {
                    if (
                        primera.dataset.index !== div.dataset.index &&
                        primera.dataset.id === div.dataset.id &&
                        primera.dataset.tipo !== div.dataset.tipo
                    ) {
                        primera.style.background = "#28a745";
                        div.style.background = "#28a745";
                        primera.classList.add("completado");
                        div.classList.add("completado");

                        parejasEncontradas++;

                        // Si ya encontró todas las parejas, mostrar mensaje
                        if (parejasEncontradas === totalParejas) {
                            marcarJuegoComoCompletado(window.idJuegoActual);
                            // juego/game95100/3
                           Swal.fire({
                                title: "¡Felicidades!",
                                text: "Has encontrado todas las parejas. Has completado el Desafío",
                                icon: "success",
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = SERVERURL + "juego/" + window.idJuegoActual + "/3";
                            });

                        }
                    } else {
                        primera.innerText = "???";
                        primera.style.background = "#007bff";
                        div.innerText = "???";
                        div.style.background = "#007bff";
                    }
                    primera = null;
                    bloqueo = false;
                }, 800);
            }
        });

        container.appendChild(div);
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
