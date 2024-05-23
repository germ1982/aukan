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
  		
			<h1>Historial de Trabajos</h1>
			<hr>

				Filtro:
				<input type="text" id="InputFiltro" placeholder="Buscar Registro" onchange="buscar();" />
				<br><br>
				

			<form id="FormRegistrosPendientes" name="FormRegistrosPendientes" method="post" action="RegistroVerCerrado.php">
				<?php CrearRadios();?> 
				<hr>
				<div id="resultadoBusqueda"></div>
				<hr>

			</form>
  </body>

</html>


<script type="text/javascript">
	
	buscar();

	function verBoton(clicked_id)
		{
			var aux = clicked_id;
			location.href="RegistroVerCerrado.php?VarIdRegistro="+aux;
		}


	function buscar() 
		{
			var textoBusqueda = document.getElementById('InputFiltro').value;
			var TipoBusqueda =  getRadioButtonSelectedValue(document.FormRegistrosPendientes.RadioTipo);
			
					$.post("MostrarRegistrosCerrados.php", {valorBusqueda: textoBusqueda, valorTipo: TipoBusqueda}, function(mensaje) 
							{
								$("#resultadoBusqueda").html(mensaje);
							}
						); 

		}

	function getRadioButtonSelectedValue(ctrl)
		{
			for(i=0;i<ctrl.length;i++)
				if(ctrl[i].checked) return ctrl[i].value;
		}
</script>

<?php 

function CrearRadios()
	{
		require_once '../../config/db.php';
		include_once '../Lib/FuncionesComunes.php';
		
		$Consulta = "SELECT * from sds_reg_tipo";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($Consulta);
		


		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{
				echo "<input type='radio' id= 'RadioTodo' name='RadioTipo' value='Todo' onchange='buscar();' checked>Todo"; 
				echo "<input type='radio' id= 'RadioIncidencias' name='RadioTipo' value='Incidencias' onchange='buscar();'>Incidencias"; 
				
				while ($result = $dbh->Registro())
				{
					
					echo "<input type='radio' id= 'Radio".$result['descripcion']."' name='RadioTipo' value='".$result['descripcion']."' onchange='buscar();'>".$result['descripcion']; 

				}
			}

		$dbh->Cerrar();
		$dbh = NULL;
	}

?> 