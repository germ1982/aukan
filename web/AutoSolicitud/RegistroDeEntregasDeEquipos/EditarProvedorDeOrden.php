<!doctype html>
<html lang="es">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/jquery-2.0.3.min.js" type="text/javascript"></script>   
    <script src="../Lib/FuncionesComunes.js" type="text/javascript"></script>   
    <link href="../Css/Awesomplete.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/awesomplete.min.js"></script>
</head>

<body onload="SetControles()">
	
	<?php 
		include_once 'db.php';
		include_once '../Lib/FuncionesComunes.php';
	?>

	<datalist id="ListaProvedores"> <?php GenerarListadoProvedores() ?> </datalist>

	<h1>Modificar Provedor de la Orden</h1>
	<hr>
	<p id="TextoHtml"></p> 
	<input class="awesomplete" list="ListaProvedores" id="InputListaProvedor" style="width:400px;" placeholder="Provedor" required/>
	<hr>
	<button type="button" name="GuardarProvedor" onclick='ValidarDatos();''>Guardar</button>
	<button type="button" name="Cancelar" onclick = "EjecutarCancelar();" >Cancelar</button>

</body>

</html>

<script type="text/javascript">

	function SetControles()
		{
			var aux = document.getElementById('VarTexto').value;
			document.getElementById("TextoHtml").innerHTML = aux;
			var aux = document.getElementById('VarProvedor').value;
			document.getElementById("InputListaProvedor").value = aux;
		}

	function ValidarDatos()
		{
			
			var aux = document.getElementById("InputListaProvedor").value;
	   		var DatoOriginal = document.getElementById("VarProvedor").value;
	   		
	   		if (!(DatoOriginal==aux))
	   			{
	   				if (!(ValidarDatoExistente("ListaProvedores", aux)))
	   					{return false;}
	   			}
	   		
	   		GuardarExpediente();
		}

	function GuardarExpediente()
		{	

			var IdOrdenDetalle = document.getElementById('VarIdOrdenDetalle').value;
			var IdOrden = document.getElementById('VarIdOrden').value;
			var Orden = document.getElementById('VarOrden').value;
			var IdExpediente = document.getElementById('VarIdExpediente').value;
			var IdUsuarioIngreso = document.getElementById('VarIdUsuarioIngreso').value;
			var FechaIngreso = document.getElementById('VarFechaIngreso').value;
			var Provedor = document.getElementById('InputListaProvedor').value;
			//alert(Provedor);
			var IdProvedor = $('#ListaProvedores [value="' + Provedor + '"]').data('idvalue');
			

			aux = 'SetOrden.php?VarIdOrdenDetalle=' + IdOrdenDetalle + '&VarIdOrden=' + IdOrden + '&VarOrden=' + Orden + '&VarIdExpediente=' + IdExpediente + '&VarIdUsuarioIngreso=' + IdUsuarioIngreso + '&VarFechaIngreso=' + FechaIngreso + '&VarIdProvedor=' + IdProvedor;

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
			$Provedor = getDatoPorId('Provedores', 'IdProvedor', 'Provedor', $IdProvedor);
			//echo "<br><br>IdProvedor: $IdProvedor<br>Provedor: $Provedor<br><br>";
			$Texto = "Cambiar Provedor de la orden: $Orden: ";

			echo "<input type='hidden' id='VarIdOrdenDetalle' name='VarIdOrdenDetalle' value=$IdOrdenDetalle>";
			echo "<input type='hidden' id='VarTexto' name='VarTexto' value='$Texto'>";
			echo "<input type='hidden' id='VarIdOrden' name='VarIdOrden' value=$IdOrden>";
			echo "<input type='hidden' id='VarOrden' name='VarOrden' value='$Orden'>";
			echo "<input type='hidden' id='VarIdExpediente' name='VarIdExpediente' value='$IdExpediente'>";
			echo "<input type='hidden' id='VarIdUsuarioIngreso' name='VarIdUsuarioIngreso' value='$IdUsuarioIngreso'>";
			echo "<input type='hidden' id='VarFechaIngreso' name='VarFechaIngreso' value='$FechaIngreso'>";
			echo "<input type='hidden' id='VarIdProvedor' name='VarIdProvedor' value='$IdProvedor'>";
			echo "<input type='hidden' id='VarProvedor' name='VarProvedor' value='$Provedor'>";
			
	}

function GenerarListadoProvedores() 
	{
		$Consulta = "Select * from Provedores WHERE NOT Baja = 1 order by Provedor";	
		GenerarListadoGenerico($Consulta, "IdProvedor", "Provedor", "IdProvedor");	
	}

?>