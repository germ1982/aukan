<?php
/*
 * Created on 25/06/2016
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('db.php');
include_once("../admin/funciones.php");
require("../fpdf17/fpdf.php");

//verificaLogueado();
define('FPDF_FONTPATH','../fpdf17/font/');
$pdf=new FPDF("P","mm","A4");
$pdf->Open();
$pdf->AliasNbPages();


//Query con los Datos
// open connection to the database
$base = new BaseDatos;
$base->Iniciar();

// Obiente los datos enviados por POST o GET
$data = data_submitted();
$key = @$data->key;
// utiliza el id del turno para generar el PDF

//Realizo el Select del numero de turno que se le asignara
$select = "SELECT * FROM turno_diario";
$base->Select($select);
$result = $base->registro();


$hoy = date('d/m/Y');
$hoydb = date('Ymd');


//Realizo el Select de los datos del turno
$select = "SELECT *, empresa.direccion as dir_empresa " .
		" FROM turno " .
		" INNER JOIN empresa ON turno.id_empresa = empresa.id " .
		" INNER JOIN persona ON turno.id_persona = persona.id " .
		" INNER JOIN examen_tipo ON turno.id_examen_tipo = examen_tipo.id" .
		" WHERE turno.id = $key";
$base->Select($select);
$result = $base->registro();

$cuit = $result["cuit"];
$razon_social = utf8_decode($result["razon_social"]);
$dir_empresa = utf8_decode($result["dir_empresa"]);
$nombre = utf8_decode($result["nombre"]);
$apellido = utf8_decode($result["apellido"]);
$dni = $result["documento"];
$fecha_nacimiento = formateFecha($result["fecha_nacimiento"]);
$te = $result["telefono"];
$email = $result["email"];
$servicio = $result["id_servicio"];
$foto = $result["foto"];
$tipo_examen = $result["id_examen_tipo"];
$id_puesto = $result["id_puesto"];
$fecha_ingreso = formateFecha($result["fecha_ingreso"]);

//Base de datos auxiliar
$base1 = new BaseDatos;
$base1->Iniciar();

//Agrego la pagina 6 -- Imagenes -- Eliminado por Solicitud de Cambio nro 10: Listado de prácticas de imágenes

$pdf->AddPage();
$pdf->SetLeftMargin(25);
$pdf->SetX(70);
$pdf->Image('../imagenes/CMESur_logoynombre.png');

$select1 = "SELECT descripcion FROM examen_tipo WHERE id = $tipo_examen";
	$base1->Select($select1);
	$result1 = $base1->registro();
	$nomexamen = utf8_decode($result1["descripcion"]);

$select1 = "SELECT  fecha_ingreso 
			FROM estudio 
			INNER JOIN turno ON turno.id = estudio.id_turno " .
			" INNER JOIN practica on estudio.id_practica=practica.id ".
			"WHERE id_turno = $key and practica.id in(24, 33, 65, 66, 72, 80, 113, 151) and fecha IS NOT NULL";
	$base1->Select($select1);
	$result1 = $base1->registro();
	$fecha = $result1["fecha_ingreso"];
	if (isset($fecha))
	{
		$hoy = formateFecha($fecha);
	}
	
$pdf->SetFont('Helvetica','B',10);
$pdf->SetY(35);
$pdf->Cell(60,8,"Estudio nro: $key",1,0,'',0);
$pdf->Cell(60,8,"Tipo examen: $nomexamen",1,0,'',0);
$pdf->Cell(55,8,"Fecha: $hoy",1,0,'',0);
$pdf->Ln();

//Datos del paciente
$pdf->Cell(120,6,"Paciente: $dni - $apellido, $nombre",1,0,'',0);
$pdf->Cell(55,6,"Fecha nac.: $fecha_nacimiento",1,0,'',0);
$pdf->Ln();

$select1 = "SELECT descripcion FROM puesto WHERE id = $id_puesto";
$base1->Select($select1);
$result1 = $base1->registro();
$puesto = utf8_decode($result1["descripcion"]);
//Datos de la empresa
$pdf->Cell(175,6,"Empresa: $cuit - $razon_social",1,0,'',0);
$pdf->Ln();
$pdf->Cell(175,6,"Puesto: $puesto",1,0,'',0);
$pdf->Ln();
$pdf->Cell(175,6,"Examen: Consulta de Especialista",1,0,'C',0);
$pdf->Ln(10);

//Seleccion de las practicas del estudio
$select1 = "SELECT practica.descripcion, estudio.observaciones " .
				" FROM practica".
                " INNER JOIN estudio on estudio.id_practica=practica.id ".
				" WHERE estudio.id_turno = $key and practica.id in(24, 33, 65, 66, 72, 80, 113, 151) ";
                " order by practica.descripcion";
$base1->Select($select1);
//$result1 = $base1->registro();

$pdf->Ln(6);
//$pdf->Cell(40,10,"$select1",0,0,'',0);
while($result1 = $base1->registro())
{
	$practica = utf8_decode($result1["descripcion"]);
	$obs = utf8_decode($result1["observaciones"]);
	
	$pdf->SetFont('Helvetica','B',10);
	$pdf->Cell(175,6,"$practica",0,0,'',0);
	$pdf->Ln(7);
	$pdf->SetFont('Helvetica','',10);
	$pdf->MultiCell(175,6,"$obs",0,'J',0);
	$pdf->Ln(7);

};

$pdf->Ln(7);
$pdf->SetFont('Helvetica','',10);
$pdf->Cell(150,6,"Firma Profesional",0,0,'R',0);
$pdf->Ln(14);

$pdfnom = $key.'.pdf';
//$pdf->Output("../pdfs/imagenes/$pdfnom", 'F');
$pdf->Output();
?>
