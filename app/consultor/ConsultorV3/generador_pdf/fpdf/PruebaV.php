<?php

require('./fpdf.php');

class PDF extends FPDF
{
   
   function Header()
   {
      include ('../conexion.php'); 
      
      
      if (!$conexion) {
          die("Error de conexión: " . $conexion->connect_error);
      }

     
      $insert = "SELECT * FROM pandillas";
      $consulta_info = $conexion->query($insert);
      
      if ($consulta_info) {
          $dato_info = $consulta_info->fetch_object();
      } else {
          die("Error en la consulta: " . $conexion->error);
      }

      $consulta_pandillas = "SELECT * FROM pandillas WHERE nombre";
      $consulta_pandillas_info = $conexion->query($consulta_pandillas);
      
      if ($consulta_pandillas_info) {
          $dato_pandillas = $consulta_pandillas_info->fetch_object();
      } else {
          die("Error en la consulta de Los Lobos: " . $conexion->error);
      }

      
      $this->Image('logo-removebg-preview.png', 185, 5, 20);
      $this->SetFont('Arial', 'B', 12);
      $this->Cell(45);
      $this->SetTextColor(0, 0, 0); 
      $this->Cell(110, 15, utf8_decode('reporte de pandillas'), 1, 1, 'C', 0); 
      $this->Ln(3); 

      
      $this->SetTextColor(103); 
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(50);
      $this->Cell(100, 10, utf8_decode("REPORTE DE PANDILLA"), 0, 1, 'C', 0);
      $this->Ln(7);
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(85, 10 ,utf8_decode("lider de la pandilla : ".$dato_pandillas->lider), 0, 0, '', 0);
      $this->Ln(10);
      $this->SetFont('Arial','B',10);
      $this->Cell(85, 10 ,utf8_decode("descripcion: ".$dato_pandillas->descripcion), 0, 0, '', 0);
      $this->Ln(10);
      
     
      $this->SetFont('Arial', 'B', 12); 
      $this->SetFillColor(228, 100, 0); 
      $this->SetTextColor(255, 255, 255); 
      $this->SetDrawColor(163, 163, 163); 

   
      $this->Cell(18, 10, utf8_decode('N°'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('Nombre'), 1, 0, 'C', 1);  
      $this->Cell(40, 10, utf8_decode('Líder'), 1, 0, 'C', 1);
      $this->Cell(30, 10, utf8_decode('Integrantes'), 1, 0, 'C', 1);
      $this->Cell(30, 10, utf8_decode('Peligrosidad'), 1, 0, 'C', 1);
      $this->Cell(70, 10, utf8_decode('Horario Reunión'), 1, 0, 'C', 1);
      $this->Cell(40, 10, utf8_decode('Fecha Aniversario'), 1, 1, 'C', 1);

      $this->Ln(2); 
   }

 
   function Footer()
   {
      $this->SetY(-15); 
      $this->SetFont('Arial', 'I', 8); 
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
      $this->SetY(-15); 
      $this->SetFont('Arial', 'I', 8); 
      $hoy = date('d/m/Y');
      $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); 
   }
}

include ('../conexion.php');

$pdf = new PDF();
$pdf->AddPage(); 
$pdf->AliasNbPages(); 

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); 

$consulta_reporte = $conexion->query("SELECT * FROM pandillas WHERE nombre");

if ($consulta_reporte) {
    while ($datos = $consulta_reporte->fetch_object()) {
        $i++; 
        $pdf->Cell(18, 10, utf8_decode($i), 1, 0, 'C', 0);
        $pdf->Cell(50, 10, utf8_decode($datos->nombre ?? 'N/A'), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos->lider ?? 'N/A'), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($datos->numero_aproximado_de_integrantes ?? 'N/A'), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($datos->peligrosidad ?? 'N/A'), 1, 0, 'C', 0);
        $pdf->Cell(70, 10, utf8_decode($datos->Horario_de_reunion ?? 'N/A'), 1, 0, 'C', 0);
        $pdf->Cell(40, 10, utf8_decode($datos->fecha_de_aniversario ?? 'N/A'), 1, 1, 'C', 0);
    }
} else {
    die("Error en la consulta: " . $conexion->error);
}

// Genera el archivo PDF para visualizar
$pdf->Output('Prueba.pdf', 'I'); 

?>
