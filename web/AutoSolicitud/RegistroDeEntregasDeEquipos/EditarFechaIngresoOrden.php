<!doctype html>
<html lang="es">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/jquery-2.0.3.min.js" type="text/javascript"></script>   
    <script src="../Lib/FuncionesComunes.js" type="text/javascript"></script>   
</head>

<body onload="SetControles()">
	
	<?php 
		include_once 'db.php';
		include_once '../Lib/FuncionesComunes.php';
	?>
	
	<h1>Modificar Fecha de Ingreso de la Orden</h1>
	<hr>
	<p id="TextoHtml"></p> 
	<input type="date" id="InputFechaIngreso" required/>
	<hr>
	<button type="button" name="GuardarNumero" onclick='GuardarExpediente();''>Guardar</button>
	<button type="button" name="Cancelar" onclick = "EjecutarCancelar();" >Cancelar</button>

</body>

</html>

<script type="text/javascript">

	function SetControles()
		{
			var aux = document.getElementById('VarTexto').value;
			document.getElementById("TextoHtml").innerHTML = aux;
			var aux = document.getElementById('VarFechaIngreso').value;
			document.getElementById("InputFechaIngreso").value = aux;
		}

	function GuardarExpediente()
		{	

			var IdOrdenDetalle = document.getElementById('VarIdOrdenDetalle').value;
			var IdOrden = document.getElementById('VarIdOrden').value;
			var Orden = document.getElementById('VarOrden').value;
			var IdExpediente = document.getElementById('VarIdExpediente').value;
			var IdUsuarioIngreso = document.getElementById('VarIdUsuarioIngreso').value;
			var FechaIngreso = document.getElementById('InputFechaIngreso').value;
			var IdProvedor = document.getElementById('VarIdProvedor').value;

			aux = 'SetOrden.php?VarIdOrdenDetalle=' + IdOrdenDetalle + '&VarIdOrden=' + IdOrden + '&VarOrden=' + Orden + '&VarIdExpediente=' + IdExpediente + '&VarIdUsuarioIngreso=' + IdUsuarioIngreso + '&VarFechaIngreso=' + FechaIngreso + '&VarIdProvedor=' + IdProvedor;
			//alert(aux);
			location.href=aux;		
		}

	function EjecutarCancelar()
	{
		var IdElemento = document.getElementById('VarIdOrdenDetalle').value;
		var aux = 'MenuOpcionesOrden.php?VarIdOrdenDetalle=' + IdElemento;
		location.href=aux;
	}
</script>

<?php
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

PrepararFormulario();

function PrepararFormulario()
	{
			$data = data_submitted();
			//print_object($data);
			$IdOrdenDetalle = $data->VarIdOrdenDetalle;
			$IdOrden = getDatoPorId('VistaEntregasPendientes', 'IdOrdenDetalle', 'IdOrden', $IdOrdenDetalle);
			$Orden = getDatoPorId('VistaEntregasPendientes', 'IdOrdenDetalle', 'Orden', $IdOrdenDetalle);
			$IdExpediente = getDatoPorId('VistaEntregasPendientes', 'IdOrdenDetalle', 'IdExpediente', $IdOrdenDetalle);
			$FechaIngreso = getDatoPorId('Ordenes', 'IdOrden', 'FechaIngreso', $IdOrden);
			$IdUsuarioIngreso = getDatoPorId('Ordenes', 'IdOrden', 'IdUsuarioIngreso', $IdOrden);
			$IdProvedor = getDatoPorId('Ordenes', 'IdOrden', 'IdProveedor', $IdOrden);
			$Texto = "Cambiar fecha de ingreso de la orden: $Orden: ";

			echo "<input type='hidden' id='VarIdOrdenDetalle' name='VarIdOrdenDetalle' value=$IdOrdenDetalle>";
			echo "<input type='hidden' id='VarTexto' name='VarTexto' value='$Texto'>";
			echo "<input type='hidden' id='VarIdOrden' name='VarIdOrden' value=$IdOrden>";
			echo "<input type='hidden' id='VarOrden' name='VarOrden' value='$Orden'>";
			echo "<input type='hidden' id='VarIdExpediente' name='VarIdExpediente' value='$IdExpediente'>";
			echo "<input type='hidden' id='VarIdUsuarioIngreso' name='VarIdUsuarioIngreso' value='$IdUsuarioIngreso'>";
			echo "<input type='hidden' id='VarFechaIngreso' name='VarFechaIngreso' value='$FechaIngreso'>";
			echo "<input type='hidden' id='VarIdProvedor' name='VarIdProvedor' value='$IdProvedor'>";
			
			
	}


?>