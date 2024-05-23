<?php
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

$data = data_submitted();
//print_object($data);


$Filtro = $data->valorBusqueda;

if ($Filtro =='' )
	{
		$Consulta = "SELECT * From VistaEntregasPendientes group by IdOrden order by Expediente, Orden";
	}
else
	{
		$Consulta = "SELECT * From VistaEntregasPendientes Where Expediente like '%$Filtro%' or Orden like '%$Filtro%' or Insumos like '%$Filtro%' order by Area, Expediente, Orden group by IdOrden";
	}
//echo "<br>$Consulta<br>";
$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($Consulta);

echo "<table id='TablaRegistros' border='1'>";
echo "<tr>";
	echo "<th>Ingreso</th>";
	echo "<th>Usuario/Area Solicitante</th>";
    echo "<th>Expediente</th>";
    echo "<th>Orden</th>";
    echo "<th>Provedor</th>";
    echo "<th>Insumo</th>";
    echo "<th>Receptor</th>";

echo "</tr>";

if (!$result)
	{
		echo "<p>Error en la consulta.</p>";
	}
else
	{
		while ($result = $dbh->Registro())
		{
			$Id = $result['IdOrden'];
			echo "<tr>";
				echo "<td>".$result['Fecha']."</td>";

				$aux = $result['IdUsuario'];
				$aux = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $aux);
				$aux = $aux." - ".$result['Area'];
				echo "<td>".$aux."</td>";
				echo "<td>".$result['Expediente']."</td>";
				echo "<td>".$result['Orden']."</td>";
				echo "<td>".$result['Provedor']."</td>";
				echo "<td>";MostrarInsumos($Id);echo "</td>";
				//echo "<td>".$result['Insumos']."</td>";
				echo "<td>".$result['Usuario']."</td>";

				//echo "<td><input type=submit value='Opciones' id=".$result['IdOrdenDetalle']." onclick='VerOpciones(this.id)'></td>";
				echo "<td><input type=submit value='Opciones' id=".$result['IdOrdenDetalle']." onclick='VerOpciones(this.id)'></td>";

			echo "</tr>";
		}
	}

echo "</table>";
$dbh->Cerrar();
$dbh = NULL;



function MostrarInsumos($IdOrden)
	{
		$consulta = "Select * from VistaEntregasPendientes where IdOrden = $IdOrden";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($consulta);
		while ($result = $dbh->Registro())
			{
					echo "<li>".$result['Insumos']."<br>";
			}
		$dbh->Cerrar();
		$dbh = NULL;
	}
?>
