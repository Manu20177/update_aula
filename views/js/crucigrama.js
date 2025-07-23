document.addEventListener("DOMContentLoaded", () => {
	const container = document.getElementById("crucigrama-container");
	let palabras = window.palabrasCrucigrama;

	if (!Array.isArray(palabras) || palabras.length === 0) return;

	// Limpiar palabras
	palabras = palabras.map(p => ({
		...p,
		palabra: p.palabra
			.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
			.replace(/[^A-Z0-9Ñ]/gi, '')
			.toUpperCase()
	}));

	const palabraMasLarga = Math.max(...palabras.map(p => p.palabra.length));
	const totalLetras = palabras.reduce((acc, p) => acc + p.palabra.length, 0);
	const GRID_SIZE = Math.max(15, palabraMasLarga + 2, Math.ceil(Math.sqrt(totalLetras * 2)) + 3);

	const grid = Array.from({ length: GRID_SIZE }, () => Array(GRID_SIZE).fill(null));
	const colocadas = [];
	const direcciones = { H: [0, 1], V: [1, 0] };

	function puedeColocar(palabra, fila, col, dir) {
		const [df, dc] = direcciones[dir];
		for (let i = 0; i < palabra.length; i++) {
			const f = fila + i * df;
			const c = col + i * dc;
			if (f < 0 || f >= GRID_SIZE || c < 0 || c >= GRID_SIZE) return false;
			const letraActual = grid[f][c];
			if (letraActual && letraActual !== palabra[i]) return false;
		}
		return true;
	}

	function colocarPalabra(palabra, fila, col, dir, numero) {
		const [df, dc] = direcciones[dir];
		for (let i = 0; i < palabra.length; i++) {
			const f = fila + i * df;
			const c = col + i * dc;
			grid[f][c] = palabra[i];
		}
		colocadas.push({ palabra, fila, col, dir, numero });
	}

	// Intentar colocar palabra usando letra inicial
	function colocarPorLetraInicial(palabra, numero) {
		const letraInicial = palabra[0];
		for (let i = 0; i < GRID_SIZE; i++) {
			for (let j = 0; j < GRID_SIZE; j++) {
				if (grid[i][j] === letraInicial) {
					if (puedeColocar(palabra, i, j, "H")) {
						colocarPalabra(palabra, i, j, "H", numero);
						return true;
					}
					if (puedeColocar(palabra, i, j, "V")) {
						colocarPalabra(palabra, i, j, "V", numero);
						return true;
					}
				}
			}
		}
		return false;
	}

	// Intentar colocar palabra en primer espacio libre horizontal o vertical
	function colocarLibre(palabra, numero) {
		for (let i = 0; i < GRID_SIZE; i++) {
			for (let j = 0; j < GRID_SIZE; j++) {
				if (puedeColocar(palabra, i, j, "H")) {
					colocarPalabra(palabra, i, j, "H", numero);
					return true;
				}
				if (puedeColocar(palabra, i, j, "V")) {
					colocarPalabra(palabra, i, j, "V", numero);
					return true;
				}
			}
		}
		return false;
	}

	let numero = 1;
	const primera = palabras[0];
	const start = Math.floor(GRID_SIZE / 2);
	colocarPalabra(primera.palabra, start, start, "H", numero++);

	// Intentar colocar palabras buscando cruces
	for (let i = 1; i < palabras.length; i++) {
		const nueva = palabras[i].palabra;
		let colocada = false;

		for (let j = 0; j < colocadas.length && !colocada; j++) {
			const { palabra: palabraExistente, fila, col, dir } = colocadas[j];
			const [df, dc] = direcciones[dir];

			for (let k = 0; k < palabraExistente.length && !colocada; k++) {
				const f = fila + k * df;
				const c = col + k * dc;
				const letraExistente = grid[f][c];

				for (let m = 0; m < nueva.length && !colocada; m++) {
					if (nueva[m] !== letraExistente) continue;

					const nuevaDir = dir === "H" ? "V" : "H";
					const [nf, nc] = direcciones[nuevaDir];
					const startF = f - m * nf;
					const startC = c - m * nc;

					if (puedeColocar(nueva, startF, startC, nuevaDir)) {
						colocarPalabra(nueva, startF, startC, nuevaDir, numero++);
						colocada = true;
					}
				}
			}
		}
	}

	// Intentar colocar palabras no colocadas por letra inicial o libre
	const palabrasNoColocadas = palabras.filter(p =>
		!colocadas.some(c => c.palabra === p.palabra)
	);

	for (const p of palabrasNoColocadas) {
		if (colocarPorLetraInicial(p.palabra, numero)) {
			console.log("Palabra colocada con letra inicial:", p.palabra);
			numero++;
		} else if (colocarLibre(p.palabra, numero)) {
			console.log("Palabra colocada libremente:", p.palabra);
			numero++;
		} else {
			console.warn("No se pudo colocar la palabra:", p.palabra);
		}
	}

	// Determinar límites del contenido real del grid
	let minRow = GRID_SIZE, maxRow = 0, minCol = GRID_SIZE, maxCol = 0;
	for (let i = 0; i < GRID_SIZE; i++) {
		for (let j = 0; j < GRID_SIZE; j++) {
			if (grid[i][j]) {
				if (i < minRow) minRow = i;
				if (i > maxRow) maxRow = i;
				if (j < minCol) minCol = j;
				if (j > maxCol) maxCol = j;
			}
		}
	}

	// Renderizar solo el área con letras
	let html = "<table style='border-collapse: collapse; margin: 0 auto;'>";
	for (let i = minRow; i <= maxRow; i++) {
		html += "<tr>";
		for (let j = minCol; j <= maxCol; j++) {
			const letra = grid[i][j];
			let numeroCelda = "";
			const inicio = colocadas.find(p => p.fila === i && p.col === j);
			if (inicio) {
				numeroCelda = `<span style='font-size:10px; position:absolute; top:1px; left:2px;'>${inicio.numero}</span>`;
			}

			if (letra) {
				html += `<td style='border: 1px solid #000; width: 30px; height: 30px; text-align: center; position:relative; padding: 0;'>`;
				html += numeroCelda;
				html += `<input maxlength="1" data-respuesta="${letra}" style="width: 100%; height: 100%; text-align: center; font-size: 18px; border: none;" />`;
				html += "</td>";
			} else {
				html += `<td style="width: 30px; height: 30px; padding: 0;"></td>`;
			}
		}
		html += "</tr>";
	}
	html += "</table><br><button id='verificarCrucigrama' style='margin-top: 5px;'>Verificar</button>";
	container.innerHTML = html;

	// Verificar respuestas
	document.getElementById("verificarCrucigrama").addEventListener("click", () => {
		let correctas = 0;
		let total = 0;

		document.querySelectorAll("td input").forEach(input => {
			const letraCorrecta = input.dataset.respuesta;
			const valor = input.value.toUpperCase();
			if (letraCorrecta) {
				total++;
				if (valor === letraCorrecta.toUpperCase()) {
					input.style.backgroundColor = "#b2f2bb";
					correctas++;
				} else {
					input.style.backgroundColor = "#f8d7da";
				}
			}
		});

		if (correctas === total) {
			marcarJuegoComoCompletado(window.idJuegoActual);
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
		} else {
			alert("❌ Hay errores. Intenta corregirlos.");
		}
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
