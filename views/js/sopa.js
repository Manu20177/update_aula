let palabras = window.palabrasJuego || [];

document.addEventListener('DOMContentLoaded', () => {
	const container = document.getElementById('sopa-container');
	if (!container) {
		console.error("No se encontró el contenedor 'sopa-container'");
		return;
	}
	if (!Array.isArray(palabras) || palabras.length === 0) {
		console.error("No hay palabras válidas en 'window.palabrasJuego'");
		console.log("Contenido actual:", window.palabrasJuego);
		return;
	}

	const letras = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
	const palabrasOriginales = palabras.slice();
	const palabrasLimpias = palabras.map(p => p.replace(/\s+/g, '').toUpperCase());

	// Tamaño automático basado en la palabra más larga y la cantidad de letras
	const maxLongitud = Math.max(...palabrasLimpias.map(p => p.length));
	const totalCaracteres = palabrasLimpias.reduce((acc, p) => acc + p.length, 0);
	const tamano = Math.max(12, maxLongitud, Math.ceil(Math.sqrt(totalCaracteres * 2)));

	const grid = Array.from({ length: tamano }, () =>
		Array.from({ length: tamano }, () => '')
	);

	const direcciones = ['H', 'V', 'D'];

	function colocarPalabra(palabra) {
		let intentos = 0;
		while (intentos < 100) {
			intentos++;
			let direccion = direcciones[Math.floor(Math.random() * direcciones.length)];
			let fila = Math.floor(Math.random() * tamano);
			let col = Math.floor(Math.random() * tamano);
			let ok = true;

			if (direccion === 'H' && col + palabra.length <= tamano) {
				for (let i = 0; i < palabra.length; i++) {
					if (grid[fila][col + i] && grid[fila][col + i] !== palabra[i]) {
						ok = false; break;
					}
				}
				if (ok) {
					for (let i = 0; i < palabra.length; i++) {
						grid[fila][col + i] = palabra[i];
					}
					return true;
				}
			} else if (direccion === 'V' && fila + palabra.length <= tamano) {
				for (let i = 0; i < palabra.length; i++) {
					if (grid[fila + i][col] && grid[fila + i][col] !== palabra[i]) {
						ok = false; break;
					}
				}
				if (ok) {
					for (let i = 0; i < palabra.length; i++) {
						grid[fila + i][col] = palabra[i];
					}
					return true;
				}
			} else if (direccion === 'D' && fila + palabra.length <= tamano && col + palabra.length <= tamano) {
				for (let i = 0; i < palabra.length; i++) {
					if (grid[fila + i][col + i] && grid[fila + i][col + i] !== palabra[i]) {
						ok = false; break;
					}
				}
				if (ok) {
					for (let i = 0; i < palabra.length; i++) {
						grid[fila + i][col + i] = palabra[i];
					}
					return true;
				}
			}
		}
		return false;
	}

	palabrasLimpias.forEach((palabra, idx) => {
		if (!colocarPalabra(palabra)) {
			console.warn(`⚠️ No se pudo colocar la palabra: ${palabrasOriginales[idx]}`);
		}
	});

	// Rellenar con letras aleatorias
	for (let i = 0; i < tamano; i++) {
		for (let j = 0; j < tamano; j++) {
			if (!grid[i][j]) {
				grid[i][j] = letras[Math.floor(Math.random() * letras.length)];
			}
		}
	}

	// Dibujar tabla
	let html = '<table id="tabla-sopa" style="border-collapse: collapse; user-select: none;">';
	for (let i = 0; i < tamano; i++) {
		html += '<tr>';
		for (let j = 0; j < tamano; j++) {
			html += `<td data-fila="${i}" data-col="${j}" style="border: 1px solid #999; width: 30px; height: 30px; text-align: center; font-size: 18px; cursor:pointer;">${grid[i][j]}</td>`;
		}
		html += '</tr>';
	}
	html += '</table>';
	container.innerHTML = html;

	let seleccion = [];
	let isDragging = false;

	document.querySelectorAll('#tabla-sopa td').forEach(td => {
		td.addEventListener('mousedown', () => {
			if (td.classList.contains('encontrado')) return;
			seleccion = [td];
			td.style.backgroundColor = '#aaf';
			isDragging = true;
		});

		td.addEventListener('mouseenter', () => {
			if (!isDragging || td.classList.contains('encontrado')) return;
			if (!seleccion.includes(td)) {
				seleccion.push(td);
				td.style.backgroundColor = '#aaf';
			}
		});

		td.addEventListener('mouseup', () => {
			if (!isDragging) return;
			isDragging = false;
			validarPalabra();
		});
	});

	document.addEventListener('mouseup', () => {
		if (isDragging) {
			isDragging = false;
			validarPalabra();
		}
	});

	function validarPalabra() {
		let palabraFormada = seleccion.map(td => td.textContent).join('');
		let palabraInversa = seleccion.map(td => td.textContent).reverse().join('');

		let index = palabrasLimpias.findIndex(p =>
			p === palabraFormada || p === palabraInversa
		);

		if (index !== -1) {
			// Palabra correcta
			seleccion.forEach(td => {
				td.style.backgroundColor = '#5cb85c';
				td.classList.add('encontrado');
			});
			let li = document.querySelector(`#lista-palabras li[data-palabra="${palabrasOriginales[index]}"]`);
			if (li) li.style.textDecoration = 'line-through';
			palabrasOriginales.splice(index, 1);
			palabrasLimpias.splice(index, 1);

			if (palabrasLimpias.length === 0) {
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
			}
		} else {
			// Palabra incorrecta
			seleccion.forEach(td => {
				if (!td.classList.contains('encontrado')) {
					td.style.backgroundColor = '';
				}
			});
		}
		seleccion = [];
	}

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
