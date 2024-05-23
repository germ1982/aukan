<?php

require_once '../Lib/FuncionesComunes.php';
require_once '../Lib/PdfPlantillaInstitucional.php';
require_once 'db.php';

$data = data_submitted();
$IdOrden = $data->VarIdOrden;
$Expediente = getDatoPorId('VistaEquiposEntregados', 'IdOrden', 'Expediente', $IdOrden);
$Orden = getDatoPorId('VistaEquiposEntregados', 'IdOrden', 'Orden', $IdOrden);
$Fecha = getDatoPorId('VistaEquiposEntregados', 'IdOrden', 'FechaEgreso', $IdOrden);
$Dia = substr($Fecha,0,2);
$Mes = substr($Fecha,3,2);
$Año = substr($Fecha,6,4);

$AuxParrafo = "En la Provincia de Neuquén, ciudad  de Neuquén , a los $Dia días del mes de ".GetNombreMes($Mes)." del año $Año, se hace entrega del correspondiente Bien de Capital que fue adquirido mediante el expediente Nº ".$Expediente.", según el detalle de orden de compra  nº ".$Orden.". Se entregan los siguientes elementos:";
$AuxParrafo = utf8_decode($AuxParrafo);

$pdf=new PDF("P","mm","A4");
$pdf->AddPage();

$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);
$LargoCeldaSola = 170;

$pdf->Ln(35);
$pdf->SetFont('Arial','BU',12);
$pdf->Cell($LargoCeldaSola,6,"ACTA ENTREGA DE INSUMO INFORMATICO",0,0,'C');


$pdf->Ln();$pdf->Ln();
$pdf->SetFont('Helvetica','',12);
$pdf->MultiCell(0,6,$AuxParrafo,0,'J');
$pdf->Ln();


$consulta = "Select * from VistaEquiposEntregados where IdOrden = $IdOrden";
$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($consulta);

	$pdf->SetFont('Arial','B',12);
	$pdf->Cell($LargoCeldaSola,6,'DESCRIPCION DEL BIEN',1,0,'C');
	$pdf->Ln();
	$pdf->SetFont('Helvetica','',10);
	while ($result = $dbh->Registro())
		{
		//		$pdf->Cell($LargoCeldaSola,6,$result['Insumos'],1,0,'L');
				$pdf->MultiCell(0,6,$result['Insumos'],1,'J');
				//$pdf->Ln();
		}

$dbh->Cerrar();
$dbh = NULL;

$LargoCeldaA =50;
$LargoCeldaB =120;
$pdf->Ln();$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell($LargoCeldaSola,6,'RESPONSABLE DEL BIEN:',0,0,'L');

$pdf->Ln();
$pdf->Cell($LargoCeldaA,6,"APELLIDO Y NOMBRE",1,0,'L');
$pdf->Cell($LargoCeldaB,6,"",1,0,'L');

$pdf->Ln();
$pdf->Cell($LargoCeldaA,6,"DNI",1,0,'L');
$pdf->Cell($LargoCeldaB,6,"",1,0,'L');

$pdf->Ln();
$pdf->Cell($LargoCeldaA,6,utf8_decode("Nº EMPLEADO"),1,0,'L');
$pdf->Cell($LargoCeldaB,6,"",1,0,'L');

$pdf->Ln();
$pdf->Cell($LargoCeldaA,6,"DESTINO",1,0,'L');
$pdf->Cell($LargoCeldaB,6,"",1,0,'L');

$pdf->Ln();$pdf->Ln();$pdf->Ln();
$pdf->SetFont('Arial','',12);
$AuxParrafo = 'No siendo para más se da por finalizado el acto leída la presente por las partes intervinientes. - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -';
$pdf->MultiCell(0,10,utf8_decode($AuxParrafo),0,'J');
$pdf->Ln();$pdf->Ln();$pdf->Ln();
$pdf->Cell($LargoCeldaSola/2,6,'____________________________',0,0,'L');
$pdf->Cell($LargoCeldaSola/2,6,'____________________________',0,0,'R');
$pdf->Ln();
$pdf->Cell($LargoCeldaSola/2,6,'             Firma de Receptor',0,0,'L');
$pdf->Cell($LargoCeldaSola/2,6,'Firma de Personal Informatica    ',0,0,'R');

$pdf->Output();

?>
