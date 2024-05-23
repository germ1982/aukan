<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdOrdenDetalle = $data->VarIdOrdenDetalle;
$IdOrden = $data->VarIdOrden;
$CantidadInsumo = $data->VarCantidadInsumo;



$consulta = "Update OrdenDetalle Set CantidadInsumo = $CantidadInsumo WHERE IdOrdenDetalle = $IdOrdenDetalle";

echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;

header("Location: MenuEditarInsumosDeOrden.php?VarIdOrden=$IdOrden");

?>
