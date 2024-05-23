<?php

require_once '../Lib/FuncionesComunes.php';
require_once '../Lib/PdfPlantillaInstitucionalNoInformatica.php';

$data = data_submitted();
//print_object($data);
$Dni = $data->VarDni;
$Apellido = $data->VarApellido;
$Nombres = $data->VarNombres;
$Clase = $data->VarClase;
$Domicilio = $data->VarDomicilio;
$Numero= $data->VarNumero;
$Localidad = $data->VarLocalidad;
$Voucher  = $data->VarVoucher;
$IdReferente  = $data->VarIdReferente;
$IdFicha  = $data->VarIdFicha;
$Lote  = $data->VarLote;
$AuxParrafo = "$Dni";
$AuxParrafo = utf8_decode($AuxParrafo);

$pdf=new PDF("P","mm","A4");
$pdf->AddPage();

$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);
$LargoCeldaSola = 170;


$pdf->Ln(20);
$pdf->SetFont('Arial','',8);
$pdf->Cell($LargoCeldaA,6,utf8_decode("Número: $IdFicha"),0,0,'R');
$pdf->Ln();
$pdf->SetFont('Arial','',8);
$pdf->Cell($LargoCeldaA,6,"Serie: $IdReferente",0,0,'R');

$pdf->Ln(10);
$pdf->SetFont('Arial','B',16);
$pdf->Cell($LargoCeldaSola,6,"$Voucher",0,0,'C');

$LargoCeldaA=50;
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell($LargoCeldaA,6,"BENEFICIARIO",0,0,'L');
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaA,6,"Apellido:",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell($LargoCeldaA,6,utf8_decode("$Apellido"),0,0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaA,6,"Nombres:",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell($LargoCeldaA,6,utf8_decode("$Nombres"),0,0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaA,6,"DNI:",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell($LargoCeldaA,6,"$Dni",0,0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaA,6,utf8_decode("Año Nacimiento:"),0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell($LargoCeldaA,6,"$Clase",0,0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaA,6,"Domicilio:",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell($LargoCeldaA,6,utf8_decode("$Domicilio"),0,0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaA,6, utf8_decode ("Número:"),0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell($LargoCeldaA,6,"$Numero",0,0,'L');

$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaA,6,"Localidad:",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell($LargoCeldaA,6,utf8_decode("$Localidad"),0,0,'L');

$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaA,6,"Lote / Tarjeta:",0,0,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell($LargoCeldaA,6,utf8_decode("$Lote"),0,0,'L');

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

$pdf->SetLeftMargin(30);
$pdf->SetRightMargin(30);
$pdf->SetFont('Arial','',8);
$pdf-> MultiCell(0, 6, utf8_decode("1-	El Ministerio de Trabajo, Desarrollo Social y Seguridad le otorga al beneficiario una tarjeta precargada de pesos mil ($1000), aprobada en la presente solicitud en carácter de complemento alimentario, en cumplimiento de sus funciones como organismo de aplicación de las leyes provinciales y en el marco de un programa social en función de la situación de vulnerabilidad de la persona indicada.") );
$pdf-> MultiCell(0, 6, utf8_decode("2-	El beneficiario deberá adjuntar al presente formulario fotocopia del Documento Nacional de Identidad."));
$pdf-> MultiCell(0, 6, utf8_decode("3-	El beneficiario tendrá 30 (treinta) días corridos a partir de la fecha de emisión para hacer uso de la tarjeta. Caso contario derivará en la caducidad de la misma. Solo se podrá adquirir productos alimentarios y/o productos de higiene en los comercios habilitados para tal fin. La tarjeta es de uso personal e intransferible."));
$pdf-> MultiCell(0, 6, utf8_decode("4-	En caso de producirse la caducidad establecida en la presente solicitud, el beneficiario deberá solicitar la tarjeta nuevamente al funcionario autorizante. "));
$pdf-> MultiCell(0, 6, utf8_decode("5-	El presente formulario deberá ser entregado a la administración con todos los datos completos y adjuntando al mismo fotocopia del Documento Nacional de Identidad."));

$pdf->Ln();$pdf->Ln();$pdf->Ln();

$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);
$pdf->rect(20,235,90,40);
$pdf->rect(110,235,90,40);

$pdf->Ln();$pdf->Ln();$pdf->Ln();
$pdf->SetXY(20,235);
$pdf->SetFont('Arial','B',10);
$AuxParrafoA = 'Declaro bajo juramento que los datos antes mencionados son correctos.';
$pdf->MultiCell($LargoCeldaSola/2,5,utf8_decode($AuxParrafoA),0,'J');
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell($LargoCeldaSola/2,6,'Firma Titular:____________________',0,0,'L');$pdf->Ln();
$pdf->Cell($LargoCeldaSola/2,6,utf8_decode('Aclaración:______________________'),0,0,'L');$pdf->Ln();
$pdf->Cell($LargoCeldaSola/2,6,'DNI:____________________________',0,0,'L');


$pdf->SetXY(110,235);
$pdf->SetFont('Arial','B',10);
$pdf->Cell($LargoCeldaSola/2,6,utf8_decode('Sólo para uso oficial.'),0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetXY(110,240);
$pdf->Cell($LargoCeldaSola/2,6,'Autoriza.',0,0,'L');
$pdf->SetXY(110,255);
$pdf->Cell($LargoCeldaSola/2,6,'Firma:__________________________',0,0,'L');
$pdf->SetXY(110,262);
$pdf->Cell($LargoCeldaSola/2,6,utf8_decode('Aclaración:______________________'),0,0,'L');
$pdf->Output();

?>
