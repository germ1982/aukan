<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdInsumo = $data->VarIdInsumo;
$IdInsumoTipo = $data->VarIdInsumoTipo;
$IdMarca = $data->VarIdMarca;
$Modelo = $data->VarModelo;
$Caracteristicas = $data->VarCaracteristicas;
$Baja= $data->VarBaja;

if ($IdInsumo==0)
	{
		$consulta = "INSERT INTO Insumos (IdInsumoTipo, IdMarca, Modelo, Caracteristicas, Baja) VALUES($IdInsumoTipo, $IdMarca, '$Modelo', '$Caracteristicas' ,$Baja)"; 
	}
else
	{
		$consulta = "Update Insumos Set IdInsumoTipo=$IdInsumoTipo, IdMarca=$IdMarca, Modelo='$Modelo', Caracteristicas='$Caracteristicas', Baja = $Baja WHERE IdInsumo = $IdInsumo";
	}

echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;


header('Location: AdministrarInsumos.php');

?>
