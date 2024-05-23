
<?php 
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

$data = data_submitted();
$Filtro = $data->valorBusqueda;

if ($Filtro =='' )
	{
		$Consulta = "Select * From Expedientes order by Expediente";
	}
else
	{
		$Consulta = "Select * From Expedientes Where Expediente like '%$Filtro%'";
	}


//echo "<br>$Consulta<br>";
$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($Consulta);

echo "<table id='TablaRegistros' border='1'>";
echo "<tr>"; 
	echo "<th>Id</th>";
    echo "<th>Expediente</th>";
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
				echo "<td>".$result['IdExpediente']."</td>"; 
				echo "<td>".$result['Expediente']."</td>"; 
				echo "<td><input type=submit value='Añadir Orden' id=".$result['IdExpediente']." onclick='VerOpciones(this.id)'></td>"; 
			echo "</tr>";   
		}
	}

echo "</table>";	
$dbh->Cerrar();
$dbh = NULL;

?>