<?php 
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

$data = data_submitted();
//print_object($data);


$IdOrden = $data->VarIdOrden;



$Consulta = "SELECT * From OrdenDetalle Where IdOrden = $IdOrden";

$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($Consulta);

echo "<table id='TablaRegistros' border='1'>";
echo "<tr>"; 
    echo "<th>Insumo</th>";
    echo "<th>Cantidad</th>";
    echo "<th>Stock</th>";
    echo "<th>Entregado</th>";
    echo "<th>Operaciones</th>";

echo "</tr>";

if (!$result) 
	{
		echo "<p>Error en la consulta.</p>"; 
	}
else 
	{
		while ($result = $dbh->Registro())
		{
			echo "<tr>"; 
				$aux = $result['IdInsumo'];
				$aux = getDatoPorId('VistaInsumos', 'IdInsumo', 'Insumo', $aux);
				echo "<td>".$aux."</td>"; 
				echo "<td>".$result['CantidadInsumo']."</td>";
				echo "<td>".$result['StockDisponible']."</td>";
				$Entregado = $result['CantidadInsumo']-$result['StockDisponible'];
				echo "<td>$Entregado</td>";
				echo "<td><input type=submit value='QuitarInsumo' id=".$result['IdOrdenDetalle']." onclick='QuitarInsumo(this.id)'> "; 	
				echo " <input type=submit value='CambiarCantidad' id=".$result['IdOrdenDetalle']." onclick='CambiarCantidad(this.id, ".$result['CantidadInsumo'].", ".$result['StockDisponible'].")'></td>"; 	
				
			echo "</tr>";   
		}
	}

echo "</table>";	
$dbh->Cerrar();
$dbh = NULL;

?>