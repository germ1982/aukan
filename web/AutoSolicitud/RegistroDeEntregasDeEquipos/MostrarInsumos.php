
<?php 
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros


$Consulta = "SELECT Insumos.IdInsumo, InsumosTipo.InsumoTipo, Marcas.Marca, Insumos.Modelo, Insumos.Caracteristicas, Insumos.Baja From Insumos INNER JOIN InsumosTipo On Insumos.IdInsumoTipo = InsumosTipo.IdInsumoTipo INNER JOIN Marcas On Insumos.IdMarca = Marcas.IdMarca order by InsumoTipo, Marca, Modelo";
//echo "<br>$Consulta<br>";
$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($Consulta);

echo "<table id='TablaInsumo' border='1'>";
echo "<tr>"; 
	echo "<th>Id</th>";
    echo "<th>Insumo</th>";
    echo "<th>Marca</th>";
    echo "<th>Modelo</th>";
    echo "<th>Caracteristicas</th>";
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
				echo "<td>".$result['IdInsumo']."</td>"; 
				echo "<td>".$result['InsumoTipo']."</td>"; 
				echo "<td>".$result['Marca']."</td>"; 
				echo "<td>".$result['Modelo']."</td>"; 
				echo "<td>".$result['Caracteristicas']."</td>"; 
				echo "<td>".$result['Baja']."</td>"; 
				echo "<td><input type=submit value='Editar' id=".$result['IdInsumo']." onclick='BotonEditar(this.id)'></td>"; 
			echo "</tr>";   
		}
	}

echo "</table>";	
$dbh->Cerrar();
$dbh = NULL;

?>