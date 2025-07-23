<?php
	const METHOD='AES-256-CBC';
	const SECRET_KEY='$SPV@2024';
	const SECRET_IV='179120';
function desencriptar($cadena_encriptada) {
    $metodo = 'AES-256-CBC';
    $clave = '$SPV@2024';
    $iv_original = '179120';

    

    // Generar clave y IV
    $key = hash('sha256', $clave, true);
    $iv = substr(hash('sha256', $iv_original, true), 0, 16);

    // Decodificar de Base64
    $datos = base64_decode($cadena_encriptada);

    // Desencriptar
    $resultado = openssl_decrypt($datos, $metodo, $key, OPENSSL_RAW_DATA, $iv);

    // Eliminar padding (si lo hay)
    $padding = ord(substr($resultado, -1));
    $resultado = substr($resultado, 0, -$padding);

    return $resultado;
}
function decryption($string){
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
			return $output;
		}
// Cadena cifrada
$cadena = 'NXVlQVZFeTRBV3pTL1R5WEFGY2dMdz09';
$texto = decryption($cadena);
echo "Texto desencriptado: " . $texto;
