<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  	<meta http-equiv="refresh" content="6000" />
    <link href="../../Css/CssGrillas.css" rel="stylesheet" type="text/css"/>
    <link href="../../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <link href="../../Css/Awesomplete.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../../Lib/FuncionesComunes.js"></script>   
    <script src="../../Lib/awesomplete.min.js"></script>
  </head>
  <body>
			<?php
				require_once '../../Lib/FuncionesComunes.php';
				VerificarSession();
			?>
			
			<h1>Control de Herramientas</h1>
			<hr>
			Filtro:
			<input type="text" name="inputFiltro" id="inputFiltro" value="" placeholder="" maxlength="30" autocomplete="off" onchange="buscar();" />
			<button type="button" name="NuevoRetiro" onclick = "location='NuevoRetiro.php'" >Nuevo Retiro</button>
			<button type="button" name="Volver" onclick = "location='../MenuRegistros.php'" >Volver</button>
			
			<hr>
			<div id="resultadoBusqueda">
				<hr>
			</div>			
  </body>

</html>



<script type="text/javascript">

	buscar();

function AñadirOrden(clicked_id)
	{
		var IdExpediente = clicked_id;
		aux = 'AñadirOrden.php?VarIdExpediente=' + IdExpediente;
		location.href=aux;	
	}


function VerOpciones(clicked_id)
	{
		var Id = clicked_id;
		aux = 'SetDevolucion.php?VarId=' + Id;
		location.href=aux;	
	}



	function buscar() 
		{
		    var textoBusqueda = $("input#inputFiltro").val();

		     if (textoBusqueda != "") 
		     	{
			        $.post("MostrarHistorial.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
			            $("#resultadoBusqueda").html(mensaje);
			        }); 
		     	} 
		     else 
		     	{ 
	        		$.post("MostrarHistorial.php", function(mensaje) 
		        	{
		            $("#resultadoBusqueda").html(mensaje);
		         	}); 
		        };
		};

</script>