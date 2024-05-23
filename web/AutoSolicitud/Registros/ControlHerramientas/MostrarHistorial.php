<?php
include_once '../../Lib/db.php';
include_once '../../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

$data = data_submitted();
//print_object($data);


$Filtro = $data->valorBusqueda;

if ($Filtro =='' )
	{
		$Consulta = "SELECT * From VistaControlHerramientas order by FechaDevolucion asc, FechaRetiro desc";
	}
else
	{
		$Consulta = "SELECT * From VistaControlHerramientas Where CargaRetiro like '%$Filtro%' or TecnicoRetiro like '%$Filtro%' or Herramientas like '%$Filtro%' or FechaRetiro like '%$Filtro%' or TipoUso like '%$Filtro%' or FechaDevolucion like '%$Filtro%' or CargaDevolucion like '%$Filtro%' order by FechaDevolucion asc, FechaRetiro desc";
	}
//echo "<br>$Consulta<br>";
$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($Consulta);

echo "<table id='TablaHerramientas' border='1'>";
echo "<tr>";
	echo "<th>Carga Retiro</th>";
	echo "<th>Tecnico Retiro</th>";
	echo "<th>Herramientas</th>";
	echo "<th>Tipo de Uso</th>";
    echo "<th>Fecha Retiro</th>";
    echo "<th>Fecha Devolucion</th>";
    echo "<th>Carga Devolucion</th>";



echo "</tr>";

if (!$result)
	{
		echo "<p>Error en la consulta.</p>";
	}
else
	{
		while ($result = $dbh->Registro())
		{
			$Id = $result['Id'];
			echo "<tr>";
				echo "<td>".$result['CargaRetiro']."</td>";
				echo "<td>".$result['TecnicoRetiro']."</td>";
				echo "<td>".$result['Herramientas']."</td>";
				echo "<td>".$result['TipoUso']."</td>";
				echo "<td>".$result['FechaRetiro']."</td>";
				echo "<td>".$result['FechaDevolucion']."</td>";
				$aux = $result['CargaDevolucion'];
				echo "<td>".$aux."</td>";
				if ($aux=='0')
					{
						echo "<td><input type=submit value='Marcar Devolucion' id=".$result['Id']." onclick='VerOpciones(this.id)'></td>";
					}
				

			echo "</tr>";
		}
	}

echo "</table>";
$dbh->Cerrar();
$dbh = NULL;



function getTipoUso($TipoUso)
	{
		switch ($TipoUso) 
			{
			    case "1":
			    	$aux = 'Laboral';
			        break;
			    case "2":
			        $aux = 'Particular';
			        break;
			    default:
			        $aux = 'Laboral';
			}
		return $aux;
	}
?>
