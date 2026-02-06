<?php
ob_start();

require __DIR__ . '/../../../php/conexion.php';  
require __DIR__ . '/fpdf/fpdf.php';            // librería FPDF (ajusta si tu ruta es distinta)

// Zona recibida desde reportes.php
$zona = $_GET['zona'] ?? '';
$zona = trim($zona);

if ($zona === '') {
    // Nada de HTML aquí, solo corta tranquilo
    ob_end_clean();
    exit;
}

// Consulta a BD usando mysqli (pandillas + ubicacion)
$sql = "
    SELECT 
        p.nombre,
        p.lider,
        p.numero_aproximado_de_integrantes,
        p.peligrosidad,
        p.Horario_de_reunion,
        p.fecha_de_aniversario
    FROM pandillas p
    JOIN ubicacion u ON p.id_Pandilla = u.id_Pandilla
    WHERE u.zona = ?
    ORDER BY p.nombre
";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    error_log('Error prepare generar_pdf.php: ' . mysqli_error($conexion));
    ob_end_clean();
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $zona);

if (!mysqli_stmt_execute($stmt)) {
    error_log('Error execute generar_pdf.php: ' . mysqli_error($conexion));
    ob_end_clean();
    exit;
}

$result = mysqli_stmt_get_result($stmt);

$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// Clase PDF personalizada
class PDF extends FPDF
{
    public $zona;

    function Header()
    {
        
        $this->Image(__DIR__ . '/../../../polis.jpg', 10, 8, 18);

        // Título
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 7, mb_convert_encoding('Criminal Nexus - Reporte de Pandillas', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 6, mb_convert_encoding('Zona: ' . $this->zona, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

        $this->Ln(5);

        // Encabezado de tabla
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(40, 40, 40);
        $this->SetTextColor(255, 255, 255);

        $this->Cell(8,  8, mb_convert_encoding('#', 'ISO-8859-1', 'UTF-8'),             1, 0, 'C', true);
        $this->Cell(40, 8, mb_convert_encoding('Nombre', 'ISO-8859-1', 'UTF-8'),        1, 0, 'L', true);
        $this->Cell(35, 8, mb_convert_encoding('Líder', 'ISO-8859-1', 'UTF-8'),         1, 0, 'L', true);
        $this->Cell(22, 8, mb_convert_encoding('Integrantes', 'ISO-8859-1', 'UTF-8'),   1, 0, 'C', true);
        $this->Cell(25, 8, mb_convert_encoding('Peligrosidad', 'ISO-8859-1', 'UTF-8'),  1, 0, 'C', true);
        $this->Cell(30, 8, mb_convert_encoding('Horario', 'ISO-8859-1', 'UTF-8'),       1, 0, 'C', true);
        $this->Cell(30, 8, mb_convert_encoding('Aniversario', 'ISO-8859-1', 'UTF-8'),   1, 1, 'C', true);

        // Reset estilos para el resto
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(0, 0, 0);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);

        $this->Cell(0, 5, mb_convert_encoding('Página ', 'ISO-8859-1', 'UTF-8') . $this->PageNo() . '/{nb}', 0, 1, 'C');

        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 4, mb_convert_encoding('Generado el: ' . date('Y-m-d H:i'), 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    }
}

// Creación del PDF
$pdf = new PDF('L', 'mm', 'A4'); // Orientación horizontal (L = Landscape)
$pdf->zona = $zona;
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 9);

// Colores alternos para filas
$fill = false;
$pdf->SetFillColor(230, 230, 230);

$contador = 1;

foreach ($rows as $row) {
    $pdf->Cell(8,  7, $contador,                                      1, 0, 'C', $fill);
    $pdf->Cell(40, 7, mb_convert_encoding($row['nombre'], 'ISO-8859-1', 'UTF-8'),                    1, 0, 'L', $fill);
    $pdf->Cell(35, 7, mb_convert_encoding($row['lider'], 'ISO-8859-1', 'UTF-8'),                     1, 0, 'L', $fill);
    $pdf->Cell(22, 7, mb_convert_encoding($row['numero_aproximado_de_integrantes'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', $fill);
    $pdf->Cell(25, 7, mb_convert_encoding($row['peligrosidad'], 'ISO-8859-1', 'UTF-8'),              1, 0, 'C', $fill);
    $pdf->Cell(30, 7, mb_convert_encoding($row['Horario_de_reunion'], 'ISO-8859-1', 'UTF-8'),        1, 0, 'C', $fill);
    $pdf->Cell(30, 7, mb_convert_encoding($row['fecha_de_aniversario'], 'ISO-8859-1', 'UTF-8'),      1, 1, 'C', $fill);

    $fill = !$fill;
    $contador++;
}

// Si no hubo filas, poner una nota
if ($contador === 1) {
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->Cell(0, 6, mb_convert_encoding('No se encontraron pandillas registradas en esta zona.', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
}

// Limpiar buffer antes de enviar el PDF
ob_end_clean();

// Descargar el PDF
$nombreArchivo = 'Reporte_Pandillas_' . preg_replace('/\s+/', '_', $zona) . '.pdf';
$pdf->Output('D', $nombreArchivo);
exit;

