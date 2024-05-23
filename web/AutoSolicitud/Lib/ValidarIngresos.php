<?php
require_once '../Lib/db.php';
include_once('../Lib/FuncionesComunes.php');
VerificarSession();

$data = data_submitted();
//print_object($data);

$IdUsuario = $_SESSION['gIdUsuario'];
$RutaVuelta = $data->VarRutaVuelta;
$RutaDestino = $data->VarRutaDestino;
$Aplicacion = $data->VarAplicacion;


$IdAplicacion = getId('Aplicaciones','IdAplicacion','Aplicacion',$Aplicacion);
$ban = 0;

$Consulta = "Select * from AplicacionesPermisos Where IdAplicacion = $IdAplicacion and IdUsuario = $IdUsuario";
//echo "$Consulta";

$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($Consulta);
if (!$result) 
	{
		echo "<p>Error en la consulta.</p>"; 
	}
else 
	{
		while ($result = $dbh->Registro())
		{
			$ban++;
		}
	}	
$dbh->Cerrar();
$dbh = NULL;
//echo "<br>$ban<br>";

if($ban>0)
	{
		header("Location: $RutaDestino");
	}
else
	{
		echo("<script>alert('Funcion de acceso limitado');</script>");
		echo("<script>window.location.href='$RutaVuelta';</script>");

	}



 
?>