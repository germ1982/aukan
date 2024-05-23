<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdOrdenDetalle = $data->VarIdOrdenDetalle;
$IdOrden = $data->VarIdOrden;
$Orden= $data->VarOrden;
$IdExpediente= $data->VarIdExpediente;
$IdUsuarioIngreso= $data->VarIdUsuarioIngreso;
$FechaIngreso= $data->VarFechaIngreso;
$IdProvedor= $data->VarIdProvedor;

$consulta = "Update Ordenes Set Orden ='$Orden', IdExpediente=$IdExpediente, IdUsuarioIngreso=$IdUsuarioIngreso, FechaIngreso='$FechaIngreso', IdProveedor=$IdProvedor WHERE IdOrden = $IdOrden";

echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;

header("Location: MenuOpcionesOrden.php?VarIdOrdenDetalle=$IdOrdenDetalle");


?>
