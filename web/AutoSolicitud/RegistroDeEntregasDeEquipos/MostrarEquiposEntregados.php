<?php 
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

$data = data_submitted();
//print_object($data);


$Filtro = $data->valorBusqueda;

if ($Filtro =='' )
	{
		$Consulta = "SELECT * From VistaEntregasRealizadas group by IdStockEntregado order by IdStockEntregado, FechaEgreso";
	}
else
	{
		$Consulta = "SELECT * From VistaEntregasRealizadas Where Expediente like '%$Filtro%' or Orden like '%$Filtro%' or Insumo like '%$Filtro%' group by IdStockEntregado order by IdStockEntregado, FechaEgreso";
	}
//echo "<br>$Consulta<br>";
$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($Consulta);

echo "<table id='TablaRegistros' border='1'>";
	echo "<tr>"; 
		echo "<th>Numero Entrega</th>";
	    echo "<th>Fecha</th>";
		echo "<th>Despacha</th>";
	    echo "<th>Recibe</th>";
		echo "<th>Articulos</th>";    
	echo "</tr>";

	if (!$result) 
		{
			echo "<p>Error en la consulta.</p>"; 
		}
	else 
		{
			$IdAux = 0;
			while ($result = $dbh->Registro())
			{
				$Id = $result['IdOrden'];

				echo "<tr>"; 
					echo "<td>".$result['IdStockEntregado']."</td>"; 
					echo "<td>".$result['FechaEgreso']."</td>"; 
					echo "<td>".$result['Despachante']."</td>"; 
					echo "<td>".$result['ReceptorEgreso']."</td>"; 
					echo "<td>";MostrarInsumos($result['IdStockEntregado']);echo "</td>"; 	
					echo "<td><input type=submit value='Ver' id=".$result['IdStockEntregado']." onclick='MostrarActaDeEntrega(this.id)'></td>";
					$IdOrdenDetalle = getDatoPorId('StockEntregadoDetalle', 'IdStockEntregado', 'IdOrdenDetalle', $result['IdStockEntregado']);
					echo "<td><input type=submit value='Añadir Orden' id='$IdOrdenDetalle' onclick='VerOpciones(this.id)'></td>"; 	
				echo "</tr>"; 
			

			}
		}

echo "</table>";	
$dbh->Cerrar();
$dbh = NULL;


	function MostrarInsumos($IdStockEntregado)
		{
			$consulta = "Select * from VistaEntregasRealizadas where IdStockEntregado = $IdStockEntregado order by Insumo, Orden, Expediente";
			//echo $consulta;
			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($consulta);
			while ($result = $dbh->Registro())
				{
						echo '<li>'.$result['Cantidad'].' - '.$result['Insumo'].'(Orden: '.$result['Orden'].' Expediente: '.$result['Expediente'].')<br>'; 
						//echo "<li>".$result['Insumo']."<br>"; 
				}
			$dbh->Cerrar();
			$dbh = NULL;
		}

?>