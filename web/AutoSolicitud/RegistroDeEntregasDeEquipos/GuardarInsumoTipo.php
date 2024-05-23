<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdInsumoTipo = $data->VarIdInsumoTipo;
$InsumoTipo = $data->VarInsumoTipo;
$Baja= $data->VarBaja;

if ($IdInsumoTipo==0)
	{
		$consulta = "INSERT INTO InsumosTipo (InsumoTipo, Baja) VALUES('$InsumoTipo', $Baja)"; 
	}
else
	{
		$consulta = "Update InsumosTipo Set InsumoTipo='$InsumoTipo', Baja = $Baja WHERE IdInsumoTipo = $IdInsumoTipo";
	}

echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;


header('Location: AdministrarInsumosTipos.php');

?>
