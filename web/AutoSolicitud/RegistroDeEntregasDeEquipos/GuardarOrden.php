<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$IdExpediente = $data->VarIdExpediente;
$Orden = $data->InputVarOrden;
$Fecha = $data->InputVarFechaIngreso;
$Fecha = str_replace("/", "-", $Fecha);
$IdProvedor = $data->InputVarIdProvedor;
$IdUsuarioIngreso = $data->InputVarIdUsuarioRecepcion;
$JSonOrden= $data->InputVarJSonOrden;



$dbh = new BaseDatos();
$dbh->Iniciar();

$consulta = "INSERT INTO Ordenes(IdExpediente, Orden, FechaIngreso, IdUsuarioIngreso, IdUsuarioEgreso, IdProveedor) VALUES ($IdExpediente,'$Orden', '$Fecha', $IdUsuarioIngreso, 0, $IdProvedor)";
echo "<br>Insert de Ordenes: $consulta<br>";
$dbh->Ejecutar($consulta);



$IdOrden = getId('Ordenes', 'IdOrden', 'Orden', $Orden);
//echo "<br>IdOrden: $IdOrden<br>";
$OrdenDetalle = json_decode($JSonOrden, true);
$Len = count($OrdenDetalle);
for($i=0;$i<$Len;$i++)
	{
		$Insumo = $OrdenDetalle[$i];
		$consulta = "INSERT INTO OrdenDetalle(IdOrden, IdInsumo, CantidadInsumo, StockDisponible) VALUES($IdOrden, $Insumo[1], $Insumo[0], $Insumo[0])";
		echo "<br>Insert de Detalles: $consulta<br>";
		$dbh->Ejecutar($consulta);
	}




$dbh->Cerrar();
$dbh = NULL;


header('Location: EquiposPendientesAEntregar.php');

?>
