<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdMarca = $data->VarIdMarca;
$Marca = $data->VarMarca;
$Baja= $data->VarBaja;

if ($IdMarca==0)
	{
		$consulta = "INSERT INTO Marcas (Marca, Baja) VALUES('$Marca', $Baja)"; 
	}
else
	{
		$consulta = "Update Marcas Set Marca='$Marca', Baja = $Baja WHERE IdMarca = $IdMarca";
	}

echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;


header('Location: AdministrarMarcas.php');

?>


