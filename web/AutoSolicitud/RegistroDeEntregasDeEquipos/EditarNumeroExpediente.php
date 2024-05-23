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

	<datalist id="ListaExpedientes"> <?php GenerarListadoGenerico("Select * From Expedientes","IdExpediente", "Expediente", "IdExpediente");?> </datalist>
	
	<h1>Modificar Numero del Expediente</h1>
	<hr>
	<p id="TextoHtml"></p> 
	<input type='text' id="InputNumeroExpediente" style="width:400px;" placeholder="Numero" required/>
	<hr>
	<button type="button" name="GuardarNumero" onclick='ValidarDatos();''>Guardar</button>
	<button type="button" name="Cancelar" onclick = "EjecutarCancelar();" >Cancelar</button>

</body>

</html>

<script type="text/javascript">

	function SetControles()
		{
			var aux = document.getElementById('VarTexto').value;
			document.getElementById("TextoHtml").innerHTML = aux;
			var aux = document.getElementById('VarExpediente').value;
			document.getElementById("InputNumeroExpediente").value = aux;
		}

	function ValidarDatos()
		{
			
			var aux = document.getElementById("InputNumeroExpediente").value;
	   		var DatoOriginal = document.getElementById("VarExpediente").value;
	   		if (!(DatoOriginal==aux))
	   			{
	   				if (ValidarDatoRepetido("ListaExpedientes", aux))
	   					{return false;}
	   			}
	   		
	   		GuardarExpediente();
		}

	function GuardarExpediente()
		{	

			var IdArea = document.getElementById('VarIdArea').value;
			var IdOrdenDetalle = document.getElementById('VarIdOrdenDetalle').value;
			var IdExpediente = document.getElementById('VarIdExpediente').value;
			var Expediente = document.getElementById('InputNumeroExpediente').value;
			var IdUsuario = document.getElementById('VarIdUsuario').value;

			aux = 'SetExpediente.php?VarIdOrdenDetalle=' + IdOrdenDetalle + '&VarIdExpediente=' + IdExpediente + '&VarExpediente=' + Expediente + '&VarIdArea=' + IdArea + '&VarIdUsuario=' + IdUsuario;
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
			$IdExpediente = getDatoPorId('VistaEntregasPendientes', 'IdOrdenDetalle', 'IdExpediente', $IdOrdenDetalle);
			$Expediente = getDatoPorId('Expedientes', 'IdExpediente', 'Expediente', $IdExpediente);
			$IdUsuario = getDatoPorId('Expedientes', 'IdExpediente', 'IdUsuario', $IdExpediente);
			$IdArea = getDatoPorId('Expedientes', 'IdExpediente', 'IdArea', $IdExpediente);
			$Texto = "Cambiar numero de expediente: $Expediente: ";

			echo "<input type='hidden' id='VarIdOrdenDetalle' name='VarIdOrdenDetalle' value=$IdOrdenDetalle>";
			echo "<input type='hidden' id='VarTexto' name='VarTexto' value='$Texto'>";

			echo "<input type='hidden' id='VarIdExpediente' name='VarIdExpediente' value='$IdExpediente'>";
			echo "<input type='hidden' id='VarExpediente' name='VarExpediente' value='$Expediente'>";
			echo "<input type='hidden' id='VarIdArea' name='VarIdArea' value='$IdArea'>";
			echo "<input type='hidden' id='VarIdUsuario' name='VarIdUsuario' value='$IdUsuario'>";
			
	}


?>