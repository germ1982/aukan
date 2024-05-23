<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdOrden = $data->VarIdOrden;
$IdInsumo = $data->VarIdInsumo;
$IdCantidad = $data->VarCantidad;



$consulta = "INSERT INTO OrdenDetalle (IdOrden, IdInsumo, CantidadInsumo, StockDisponible) VALUES($IdOrden, $IdInsumo, $IdCantidad, $IdCantidad)"; 
	
echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;

header("Location: MenuEditarInsumosDeOrden.php?VarIdOrden=$IdOrden");

?>
