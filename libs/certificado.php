<?php
// header('Content-Type: text/html; charset=utf-8');
require_once "./controllers/cursoclaseController.php";

$insVideo = new cursoclaseController();
$page=explode("/", $_GET['views']);

$code=$page[2];
$id_alumno=$page[1];
$Datosc=$insVideo->execute_single_query("
      SELECT CONCAT(e.Nombres, ' ', e.Apellidos) as Nombres, c.Titulo as Tittle, 
      DATE_FORMAT(ca.fecha_termino, '%d / %m / %Y') as fecha,tc.norma as Norma 
      FROM curso_alumno ca LEFT JOIN curso c on c.id_curso = ca.id_curso
      LEFT JOIN estudiante e on e.Codigo = ca.id_alumno 
        LEFT JOIN tipo_curso tc on tc.id_tipoc=c.Norma
        WHERE ca.id_curso='$code' && ca.id_alumno='$id_alumno';

    ");
$Datosc=$Datosc->fetchAll();
        
foreach($Datosc as $rows){
    $participante=$rows['Nombres'];
    $tema=$rows['Tittle'];
    $fecha=$rows['fecha'];
}

// 1. Desactivamos errores
error_reporting(0);
ini_set('display_errors', 0);

// 2. Iniciar buffer de salida
ob_start();

// 3. Definir SERVER_PATH dinámicamente
define('SERVER_PATH', __DIR__ . '/');



// 4. Ruta al PDF base
$templatePath = 'archivos/certificados/certificado.pdf';

// 5. Datos de prueba (reemplaza con consulta real si usas BD)
// $participante = "Juan Pérez";
// $tema         = "Gestión del Riesgo Operativo";
// $fecha        = date("d/m/Y");

// 6. Validar que exista la plantilla
if (!file_exists($templatePath)) {
    die("Plantilla no encontrada: $templatePath");
}
ob_start(); // Inicia el buffer
// 7. Cargar librerías necesarias
require_once 'libs/fpdf/fpdf.php';
require_once 'libs/fpdi/src/autoload.php';

use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();

try {
    // Importar página 1 del PDF base
    $pageCount = $pdf->setSourceFile($templatePath);
    if ($pageCount < 1) {
        throw new Exception("El PDF no contiene páginas válidas.");
    }

    $template = $pdf->importPage(1);

    // Obtener tamaño de página original
    $size = $pdf->getTemplateSize($template);
    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
    $pdf->useTemplate($template, 0, 0, $size['width']);

    // Insertar datos con fuentes y tamaños personalizados


        // Configurar fuente
    $pdf->SetFont('Times', 'BI', 40);
    $pdf->SetTextColor(0, 0, 0);

    // Posición X,Y donde comienza el "contenedor"
    $x = 75;
    $y = 105;

    // Ancho del contenedor (ajusta este valor según tu diseño)
    $ancho = 150; 

    // Altura estimada (opcional)
    $alto = 0;

    // Escribir texto centrado usando Cell()
    $pdf->SetXY($x, $y);
    $pdf->Cell($ancho, $alto, utf8_decode($participante), 0, 0, 'C');

    // Tema del curso - Times, negrita cursiva, tamaño 14
    $pdf->SetFont('Times', 'BI', 25);
    $pdf->SetTextColor(0, 0, 0); // Negro
     // Posición X,Y donde comienza el "contenedor"
    $x = 75;
    $y = 105;

    // Ancho del contenedor (ajusta este valor según tu diseño)
    $ancho = 150; 

    // Altura estimada (opcional)
    $alto = 50;

    // Escribir texto centrado usando Cell()
    $pdf->SetXY($x, $y);
    $pdf->Cell($ancho, $alto, utf8_decode($tema), 0, 0, 'C');
   
    // Fecha - Courier, cursiva, tamaño 12
    $pdf->SetFont('Times', 'BI', 16);
    $pdf->SetTextColor(0, 0, 0); // Negro
    // Posición X,Y donde comienza el "contenedor"
    $x = 75;
    $y = 105;

    // Ancho del contenedor (ajusta este valor según tu diseño)
    $ancho = 62; 

    // Altura estimada (opcional)
    $alto = 80;

    // Escribir texto centrado usando Cell()
    $pdf->SetXY($x, $y);
    $pdf->Cell($ancho, $alto, utf8_decode($fecha), 0, 0, 'C');

    // Limpiar buffers completamente
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Forzar descarga del certificado
    $pdf->Output("D", "Certificado_{$participante}.pdf");
    exit;

} catch (Exception $e) {
    while (ob_get_level()) {
        ob_end_clean();
    }
    die("Error al generar el certificado: " . $e->getMessage());
}
