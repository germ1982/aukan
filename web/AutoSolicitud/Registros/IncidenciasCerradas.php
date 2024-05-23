<!doctype html>
<html>
	<head>
		<meta http-equiv="refresh" content="6000" />
		<link href="../Css/CssGrillas.css" rel="stylesheet" type="text/css"/>
		<link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
		<script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>  
		<script type="text/javascript" src="../Lib/jquery_table.buscoloquemesaledelospeones.js"></script>
	</head>

	<body>

			<h1>Consulta de Incidencias Cerradas</h1>
			<hr>
				Filtro: 
				<input id="inputFiltro" type="text" style="width: 300px;"/>
				<button type="button" name="Volver" onclick = "location='Incidencias.php'">Volver</button>
			<hr>
			<form method="post" action="IncidenciaVerCerrada.php">
				<table id="divTabla" border='1'>
					<?php CargarGrilla();?>  
				</table> 
				<input type="hidden" id="varIncOperacion" name="varIncOperacion">
			</form>
			<hr>

	</body>

</html>


 <script type="text/javascript">

		$(document).ready(function() {
		$('#divTabla').buscoloquemesaledelospeones('inputFiltro');
		});
		 
		function verBoton(clicked_id)
			{
				var aux = clicked_id;
				//alert(aux);
				document.getElementById('varIncOperacion').value = aux;
			};
 </script>  



 <?php 

function CargarGrilla()
{
	require_once '../Lib/db.php';

    $sql1 = "SELECT date_format(RegistrosDiaADiaIncidencias.FechaIngreso, '%d/%m/%Y') AS FechaIngreso, Areas.Area AS Area, Usuarios.Usuario AS UsuarioIngreso, RegistrosDiaADiaIncidencias.TecnicoIngreso AS TecnicoIngreso, RegistrosDiaADiaIncidencias.Equipo AS Equipo, RegistrosDiaADiaIncidencias.Problema as Problema, RegistrosDiaADiaIncidencias.IdIncidencia as IdIncidencia, RegistrosDiaADiaIncidencias.Finalizada as Finalizada ";
    $sql2 = "FROM RegistrosDiaADiaIncidencias LEFT OUTER JOIN Usuarios ON RegistrosDiaADiaIncidencias.IdUsuarioIngreso = Usuarios.IdUsuario LEFT OUTER JOIN Areas ON RegistrosDiaADiaIncidencias.IdArea = Areas.IdArea ";
    $sql3 = "Where RegistrosDiaADiaIncidencias.Finalizada > 0 Order by RegistrosDiaADiaIncidencias.IdIncidencia";
    $Consulta = $sql1.$sql2.$sql3;
    //echo ("<br>$Consulta<br>");
	$dbh = new BaseDatos();
	$dbh->Iniciar();
	$result = $dbh->Select($Consulta);

		echo "<tr>"; 
	    echo "<th>Id</th>";
	    echo "<th>Fecha</th>";
	    echo "<th>Area</th>"; 
	    echo "<th>Usuario</th> ";
	    echo "<th>Receptor</th>";
	    echo "<th>Equipo</th>"; 
	    echo "<th>Problema</th> ";
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
				echo "<td>".$result['IdIncidencia']."</td>"; 
				echo "<td>".$result['FechaIngreso']."</td>"; 
				echo "<td>".$result['Area']."</td>"; 
				echo "<td>".$result['UsuarioIngreso']."</td>"; 
				echo "<td>".$result['TecnicoIngreso']."</td>"; 
				echo "<td>".$result['Equipo']."</td>"; 
				echo "<td>".$result['Problema']."</td>"; 
				echo "<td><input type='submit' value='ver' id=".$result['IdIncidencia']." onclick='verBoton(this.id)'></td>"; 
				echo "</tr>";   
			}
		}	


	$dbh->Cerrar();
	$dbh = NULL;}

?>