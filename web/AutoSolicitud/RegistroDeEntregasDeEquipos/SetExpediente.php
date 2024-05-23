<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdOrdenDetalle = $data->VarIdOrdenDetalle;
$IdExpediente = $data->VarIdExpediente;
$Expediente= $data->VarExpediente;
$IdArea= $data->VarIdArea;
$IdUsuario= $data->VarIdUsuario;

$consulta = "Update Expedientes Set Expediente ='$Expediente', IdArea=$IdArea, IdUsuario=$IdUsuario WHERE IdExpediente = $IdExpediente";

echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;

header("Location: MenuOpcionesOrden.php?VarIdOrdenDetalle=$IdOrdenDetalle");


?>
