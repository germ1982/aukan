<!doctype html>
<html>

  <head>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script> 
 	<link href="../Css/ActaDeEntrega.css" rel="stylesheet" type="text/css"/>
	<style>
		 	table {
		    border-collapse: collapse;
		    width:700px;
		}

		table, td, th {
		    border: 1px solid black;
		}
	</style>

  </head>

  <body onload="setcontroles()">

	<?php
		require_once '../Lib/FuncionesComunes.php';
		VerificarSession();
	?>
	<div align=center>
		<IMG SRC="Imagenes\Membrete.jpg" width="800px" height="80px"><br><br><br><br>
		<font face="Arial" size="3" ><b>ACTA ENTREGA DE INSUMO INFORMATICO</b></font>
		<br><br><br>

		<div Id="DivParrafo">
			<p id="ParrafoVisible" align="justify"></p>
		</div>

		<div id="DivInsumos">
			<p id="InsumosOrden" align="left"></p>
		</div>

	</div>

  </body>

</html>


<script type="text/javascript">
	
function setcontroles()
	{
			document.getElementById("ParrafoVisible").innerHTML = document.getElementById("Parrafo").value;
			document.getElementById("InsumosOrden").innerHTML = document.getElementById("VarInsumosOrden").value;	
	}

</script>


<?php 
require_once '../Lib/FuncionesComunes.php';
require_once 'db.php';
setlocale(LC_ALL,”es_ES”);

$data = data_submitted();
$IdOrden = $data->VarIdOrden;


$consulta = "SELECT * From VistaEquiposEntregados Where IdOrden = $IdOrden group by IdOrden";
//echo "$consulta";

$dbh = new BaseDatos();
$dbh->Iniciar();
$result = $dbh->Select($consulta);
$result = $dbh->Registro();
if (!$result) 
	{
		echo "<p>Error en la consulta.</p>"; 
	}
else 
	{	


		$AuxDato = "En la Provincia de Neuquén, ciudad  de Neuquén , a los ".date("d")." días del mes de ".GetNombreMes(date("m"))." del año ".date("Y").", se hace entrega del correspondiente Bien de Capital que fue adquirido mediante el expediente Nº ".$result["Expediente"].", según el detalle de orden de compra  nº ".$result["Orden"].", se entregan los siguientes elementos:";

		//echo $AuxDato;
		echo "<input type='hidden' id='Parrafo' name='Parrafo' value='$AuxDato'>";


		$AuxDato = MostrarInsumos($IdOrden);
		echo "<input type='hidden' id='VarInsumosOrden' name='VarInsumosOrden' value='$AuxDato'>";

	}	

$dbh->Cerrar();
$dbh = NULL;


function MostrarInsumos($IdOrden)
	{
		$Insumos = "<tr><th>Descripcion del bien</th></tr>";
		$consulta = "Select * from VistaEquiposEntregados where IdOrden = $IdOrden";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($consulta);
		
		while ($result = $dbh->Registro())
			{
					$Insumos = $Insumos."<tr><td>".$result['Insumos']."</td></tr>"; 
			}
	
		$dbh->Cerrar();
		$dbh = NULL;
		$Insumos = "<table>$Insumos</table>";
		//echo $Insumos;
		return $Insumos;
	}

?>