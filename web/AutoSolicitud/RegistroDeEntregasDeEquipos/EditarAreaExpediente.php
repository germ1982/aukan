<!doctype html>
<html lang="es">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Awesomplete.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/awesomplete.min.js"></script>
    <script src="../Lib/jquery-2.0.3.min.js" type="text/javascript"></script>   
    <script src="../Lib/FuncionesComunes.js" type="text/javascript"></script>   
</head>

<body onload="SetControles()">
	
	<?php 
		include_once 'db.php';
		include_once '../Lib/FuncionesComunes.php';
	?>

	<datalist id="ListaSectores"> <?php GenerarListadoSectores() ?> </datalist>

	
	<h1>Modificar Area del Expediente</h1>
	<hr>
	<p id="TextoHtml"></p> 
	<input class="awesomplete" list="ListaSectores" id="InputListaSectores" style="width:400px;" placeholder="Sector" required/>
	<hr>
	<button type="button" name="GuardarArea" onclick='ValidarDatos();''>Guardar</button>
	<button type="button" name="Cancelar" onclick = "EjecutarCancelar();" >Cancelar</button>

</body>

</html>

<script type="text/javascript">

	function SetControles()
		{
			var aux = document.getElementById('VarTexto').value;
			document.getElementById("TextoHtml").innerHTML = aux;
			var aux = document.getElementById('VarIdArea').value;
			document.getElementById("InputListaSectores").value = aux;
		}

	function ValidarDatos()
		{
			
			var area = document.querySelector('#InputListaSectores').value;
		   		if (!ValidarDatoExistente("ListaSectores", area))
		   			{return false;}
	   		
	   		GuardarExpediente();
		}

	function GuardarExpediente()
		{	
			var Area = document.getElementById("InputListaSectores").value;
			var IdArea = $('#ListaSectores [value="' + Area + '"]').data('idvalue');
			var IdOrdenDetalle = document.getElementById('VarIdOrdenDetalle').value;
			var IdExpediente = document.getElementById('VarIdExpediente').value;
			var Expediente = document.getElementById('VarExpediente').value;
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
			$Area = getDatoPorId('VistaEntregasPendientes', 'IdOrdenDetalle', 'Area', $IdOrdenDetalle);
			$Texto = "Area Iniciante de expediente $Expediente: ";

			echo "<input type='hidden' id='VarIdOrdenDetalle' name='VarIdOrdenDetalle' value=$IdOrdenDetalle>";
			echo "<input type='hidden' id='VarTexto' name='VarTexto' value='$Texto'>";

			echo "<input type='hidden' id='VarIdExpediente' name='VarIdExpediente' value='$IdExpediente'>";
			echo "<input type='hidden' id='VarExpediente' name='VarExpediente' value='$Expediente'>";
			echo "<input type='hidden' id='VarIdArea' name='VarIdArea' value='$Area'>";
			echo "<input type='hidden' id='VarIdUsuario' name='VarIdUsuario' value='$IdUsuario'>";
			
	}

function GenerarListadoSectores() 
	{
		$Consulta = "Select * from Areas WHERE NOT Referencia = 'Baja' order by Area";	
		GenerarListadoGenerico($Consulta, "IdArea", "Area", "IdArea");	
	}

?>