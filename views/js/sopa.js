let palabras = window.palabrasJuego || [];

document.addEventListener('DOMContentLoaded', () => {
	const container = document.getElementById('sopa-container');
	if (!container || !palabras || palabras.length === 0) return;

	const tamano = 12;
	const letras = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
	const grid = Array.from({ length: tamano }, () =>
		Array.from({ length: tamano }, () => '')
	);

	// Insertar palabras horizontalmente
	// Insertar palabras horizontal o vertical aleatoriamente
	palabras.forEach(palabra => {
		palabra = palabra.toUpperCase();
		let colocada = false;

		while (!colocada) {
			let direccion = Math.random() < 0.5 ? 'H' : 'V'; // H: horizontal, V: vertical
			let fila = Math.floor(Math.random() * tamano);
			let col = Math.floor(Math.random() * tamano);

			if (direccion === 'H' && col + palabra.length <= tamano) {
				let espacioLibre = true;
				for (let i = 0; i < palabra.length; i++) {
					if (grid[fila][col + i] !== '') {
						espacioLibre = false;
						break;
					}
				}
				if (espacioLibre) {
					for (let i = 0; i < palabra.length; i++) {
						grid[fila][col + i] = palabra[i];
					}
					colocada = true;
				}
			} else if (direccion === 'V' && fila + palabra.length <= tamano) {
				let espacioLibre = true;
				for (let i = 0; i < palabra.length; i++) {
					if (grid[fila + i][col] !== '') {
						espacioLibre = false;
						break;
					}
				}
				if (espacioLibre) {
					for (let i = 0; i < palabra.length; i++) {
						grid[fila + i][col] = palabra[i];
					}
					colocada = true;
				}
			}
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

	// Dibujar el grid
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
		td.addEventListener('mousedown', e => {
			if (td.classList.contains('encontrado')) return;
			seleccion = [td];
			td.style.backgroundColor = '#aaf';
			isDragging = true;
		});

		td.addEventListener('mouseenter', e => {
			if (!isDragging || td.classList.contains('encontrado')) return;
			if (!seleccion.includes(td)) {
				seleccion.push(td);
				td.style.backgroundColor = '#aaf';
			}
		});

		td.addEventListener('mouseup', e => {
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
		let index = palabras.findIndex(p => p === palabraFormada || p === palabraInversa);

		if (index !== -1) {
			// Correcta
			seleccion.forEach(td => {
				td.style.backgroundColor = '#5cb85c';
				td.classList.add('encontrado');
			});
			let li = document.querySelector(`#lista-palabras li[data-palabra="${palabras[index]}"]`);
			if (li) li.style.textDecoration = 'line-through';
			palabras.splice(index, 1);

			if (palabras.length === 0) {
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
			// Incorrecta
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