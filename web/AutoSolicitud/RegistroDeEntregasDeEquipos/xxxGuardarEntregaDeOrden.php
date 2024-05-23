<?php

//esto ya no va porque no define que se entrego, sino que anula la visualizacion de que esta pendiente al agregarle usuarios y fechas de entrega en ordenes...

require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdOrden = $data->VarIdOrden;
$FechaEgreso = $data->VarFechaEgreso;
$IdUsuarioEgreso= $data->VarIdUsuarioEgreso;
$IdUsuarioRecibeFinal= $data->VarIdUsuarioRecibeFinal;

$consulta = "Update Ordenes Set FechaEgreso ='$FechaEgreso', IdUsuarioEgreso=$IdUsuarioEgreso, IdUsuarioRecibeFinal= $IdUsuarioRecibeFinal WHERE IdOrden = $IdOrden";

echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;


header('Location: EquiposPendientesAEntregar.php');

?>
