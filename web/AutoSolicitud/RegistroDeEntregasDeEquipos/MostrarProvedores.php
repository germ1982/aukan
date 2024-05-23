
<?php 
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros


$Consulta = "Select * From Provedores order by Provedor";
//echo "<br>$Consulta<br>";
$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($Consulta);

echo "<table id='TablaRegistros' border='1'>";
echo "<tr>"; 
	echo "<th>Id</th>";
    echo "<th>Provedor</th>";
    echo "<th>Baja</th>";
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
				echo "<td>".$result['IdProvedor']."</td>"; 
				echo "<td>".$result['Provedor']."</td>"; 
				echo "<td>".$result['Baja']."</td>"; 
				echo "<td><input type=submit value='Editar' id=".$result['IdProvedor']." onclick='BotonEditar(this.id)'></td>"; 
			echo "</tr>";   
		}
	}

echo "</table>";	
$dbh->Cerrar();
$dbh = NULL;

?>