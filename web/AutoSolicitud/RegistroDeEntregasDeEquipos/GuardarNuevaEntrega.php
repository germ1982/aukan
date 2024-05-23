<?php
require_once 'db.php';
include_once('../Lib/FuncionesComunes.php');
//VerificarSession();

$data = data_submitted();
print_object($data);

$Fecha = $data->InputVarFechaEntrega;
$Fecha = str_replace("/", "-", $Fecha);
$IdUsuarioDespachante = $data->InputVarIdUsuarioDespachante;
$IdUsuarioRecepcion = $data->InputVarIdUsuarioRecepcion;
$JSonInsumos= $data->InputVarJSonInsumos;

$dbh = new BaseDatos();
$dbh->Iniciar();

$consulta = "INSERT INTO StockEntregado(FechaEgreso, IdUsuarioDespachante, 	IdUsuarioRecepcion) VALUES ('$Fecha', $IdUsuarioDespachante, $IdUsuarioRecepcion)";
echo "<br>Insert de StockEntregado: $consulta<br>";

$dbh->Ejecutar($consulta);

$IdStockEntregado = GetUltimoId('StockEntregado', 'IdStockEntregado');
echo "<br>IdStockEntregado: $IdStockEntregado<br>";

$InsumosEntregados = json_decode($JSonInsumos, true);
$Len = count($InsumosEntregados);
for($i=0;$i<$Len;$i++)
	{
		$Insumo = $InsumosEntregados[$i];
		$consulta = "INSERT INTO StockEntregadoDetalle(IdStockEntregado,IdOrdenDetalle,Cantidad) VALUES($IdStockEntregado, $Insumo[0], $Insumo[1])";
		echo "<br>Insert de Detalles: $consulta<br>";
		$dbh->Ejecutar($consulta);
		RestarStock($Insumo[0],$Insumo[3], $Insumo[1]);
	}
$dbh->Cerrar();
$dbh = NULL;


header('Location: PdfActaEntregaArticulos.php?VarIdStockEntregado='.$IdStockEntregado);
//header('Location: EquiposPendientesAEntregar.php');

function GetUltimoId($Tabla, $CampoId)
	{
		$Consulta = "Select MAX($CampoId) as Id From $Tabla";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($Consulta);
		$aux=0;
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				while ($result = $dbh->Registro())
				{
						$Aux = $result['Id'];
				}
			}
		$dbh->Cerrar();
		$dbh = NULL;
		return $Aux;
	}

function RestarStock($IdOrdenDetalle, $StockDisponible, $CantidadARestar)
	{
		$NuevoStock = $StockDisponible - $CantidadARestar;
		$consulta = "Update OrdenDetalle Set StockDisponible = $NuevoStock WHERE IdOrdenDetalle = $IdOrdenDetalle";

		echo $consulta;

		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($consulta);
		$dbh->Cerrar();
		$dbh = NULL;
	}

?>
