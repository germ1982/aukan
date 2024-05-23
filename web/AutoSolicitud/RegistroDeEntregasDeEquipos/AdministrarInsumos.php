<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/CssGrillas.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Awesomplete.css" rel="stylesheet" type="text/css"/>
    <script src="../Lib/jquery-2.0.3.min.js" type="text/javascript"></script>   
    <script src="../Lib/FuncionesComunes.js" type="text/javascript"></script>   
    <script src="../Lib/awesomplete.min.js"></script>
  </head>
  <body>
  		
			<?php
				require_once '../Lib/FuncionesComunes.php';
				VerificarSession();
			?>

			<h1>Gestion de Articulos</h1>
			
			<hr>
			
 			<div>
				<button type="button" name="NuevoInsumo" onclick="location='EditarInsumo.php?VarIdInsumo=0'">Nuevo Articulo</button>
				<button type="button" name="Volver" onclick = "location='MenuEquiposSalientes.php'" >Volver</button>
			</div>

			<hr>

			<div id="resultadoBusqueda">
				<hr>
			</div>
			
			<input type="hidden" id="InputVarIdInsumo"/>
			
			
  </body>

</html>



<script type="text/javascript">

	buscar();

	function BotonEditar(clicked_id)
		{
			var IdInsumo = clicked_id;
			location.href="EditarInsumo.php?VarIdInsumo="+IdInsumo;
		}

	function buscar() 
		{
		        $.post("MostrarInsumos.php", function(mensaje) 
		        	{
		            $("#resultadoBusqueda").html(mensaje);
		         	}); 
		}

</script>