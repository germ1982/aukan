

<?php 
include_once '../Lib/db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros
//VerificarSession();

$data = data_submitted();
//print_object($data);

$Filtro = $data->valorBusqueda;
//$Filtro =  date("d/m/Y", strtotime($Filtro));

CargarGrilla($Filtro);

function CargarGrilla($filtro)
{

    $Consulta = "SELECT * FROM risneu_movimientos_vista";


    //echo "<br>$Consulta<br>";
    
	$dbh = new BaseDatos();

	$dbh->Iniciar();
	$result = $dbh->Select($Consulta);
	
	echo "<table id='TablaRegistros' border='1'>";
	echo "<tr>"; 
	    echo "<th>Id</th>";
	    echo "<th>Persona</th>";
	    echo "<th>Documento</th>"; 
	    echo "<th>Fotocopias</th> ";
	    echo "<th>Ingresante</th>";
	    echo "<th>Ingreso</th>"; 
	    echo "<th>Egreso</th> ";
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
					echo "<td>".$result['Id']."</td>"; 
					echo "<td>".$result['Persona']."</td>"; 
					echo "<td>".$result['Documento']."</td>"; 
					echo "<td>".$result['Fotocopias']."</td>"; 
					echo "<td>".$result['UsuarioIngreso']."</td>"; 
					echo "<td>".$result['FechaIngreso']."</td>"; 
					echo "<td>".$result['FechaEgreso']."</td>"; 
					echo "<td><input type=submit value='Ver' id=".$result['IdRegistro']." onclick='verBoton(this.id)'></td>"; 
				echo "</tr>";   
			}
		}

	echo "</table>";	
	$dbh->Cerrar();
	$dbh = NULL;
	}

?>