<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdProvedor = $data->VarIdProvedor;
$Provedor = $data->VarProvedor;
$Baja= $data->VarBaja;

if ($IdProvedor==0)
	{
		$consulta = "INSERT INTO Provedores (Provedor, Baja) VALUES('$Provedor', $Baja)"; 
	}
else
	{
		$consulta = "Update Provedores Set Provedor='$Provedor', Baja = $Baja WHERE IdProvedor = $IdProvedor";
	}

echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;


header('Location: AdministrarProvedores.php');

?>


