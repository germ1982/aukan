<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/CssGrillas.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
  </head>
  <body>
  		
			<?php
				require_once '../Lib/FuncionesComunes.php';
				VerificarSession();
			?>
			<h1>Consulta de Registros Cerrados Con Filtro</h1>
			<hr>
			

				Filtro:
				<input type="text" id="InputFiltro" placeholder="algo" onchange="buscar();" />
				<button type="button" name="Volver" onclick = "location='MenuRegistros.php'" >Listo</button>


			<form id="FormRegistrosPendientes" method="post" action="RegistroVerCerrado.php">
				<hr>
				<div id="resultadoBusqueda"></div>
				<hr>

				<br>

				<input type="hidden" id="VarIdRegistro" name="VarIdRegistro">
				<input type="hidden" id="AbrirRegistro" name="AbrirRegistro" value = "Existente">
			</form>
  </body>

</html>


<script type="text/javascript">
		


	buscar();

	function verBoton(clicked_id)
		{
			var aux = clicked_id;
			//alert(aux);
			document.getElementById('VarIdRegistro').value = aux;
		}


	function buscar() {
	    var textoBusqueda = document.getElementById('InputFiltro').value;

	     if (textoBusqueda != "") {
	        $.post("MostrarRegistrosCerradosConFiltro.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
	            $("#resultadoBusqueda").html(mensaje);
	         }); 
	     } else { 
	        $("#resultadoBusqueda").html('<p>sin datos</p>');
	        };
	};


</script>

