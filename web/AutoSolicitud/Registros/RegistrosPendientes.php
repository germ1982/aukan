<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="refresh" content="1030" />
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="../Css/CssGrillas.css" rel="stylesheet" type="text/css"/>
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
  </head>
  <body onload="SetControles()">
  		
 		<h1><p id="txt_titulo">Trabajos Pendientes</p></h1>
		<hr> 

		Filtro:
		<input type="text" id="InputFiltro" placeholder="Buscar Registro" onchange="buscar();" />
		<br>

				

		<form id="FormRegistrosPendientes" method="post" action="RegistroEditar.php">
			<?php CargarDatonIniciales();?> 
			<hr>

			<?php CrearRadios();?> 

			<br><input type="checkbox" id="MostrarSoloIncidencias" name="MostrarSoloIncidencias" onchange='buscar();'>	Mostrar solo Incidencias
			<hr>

			<div id="resultadoBusqueda"></div>
			<hr>

			<input type="hidden" id="VarIdRegistro" name="VarIdRegistro">
		</form>
  </body>

</html>


<script type="text/javascript">
	
	buscar();

	function SetControles()
		{
			var aux = $("#VarTipoValue").val();
			$('#txt_titulo').html("Trabajos Pendientes: " + aux);
			var aux = 'Radio'+aux;
			$("#"+aux).prop("checked", true);
			var aux = $("#VarSoloIncidencias").val();
			if(aux==1)
				{
					$("#MostrarSoloIncidencias").prop("checked", true);
				}
			else
				{
					$("#MostrarSoloIncidencias").prop("checked", false);
				}
			
				buscar();
		}

/* 	function verBoton(clicked_id)
		{
			var aux = clicked_id;
			document.getElementById('VarIdRegistro').value = aux;
		} */

	function AbrirRegistro(IdRegistro,IdUsuario,IdTipo)
		{
			var Ruta = "RegistroEditar.php?VarIdRegistro="+IdRegistro+"&idusuario="+IdUsuario+"&VarTipo="+IdTipo;
			location.href = Ruta;
		}
	function AbrirIncidencia(IdRegistro,IdUsuario,IdTipo)
		{
			var Ruta = "Incidencia.php?idregistro="+IdRegistro+"&idusuario="+IdUsuario+"&VarTipo="+IdTipo;
			location.href = Ruta;
		}


	function buscar() 
		{
			var textoBusqueda = document.getElementById('InputFiltro').value;
			var TipoBusqueda =  $('input:radio[name=RadioTipo]:checked').val()
			var idusuario = document.getElementById('idusuario').value;
			$('#txt_titulo').html("Trabajos Pendientes: " + TipoBusqueda);
			var soloincidencias = 0;
			if (document.getElementById('MostrarSoloIncidencias').checked)
				{
					soloincidencias = 1;
				}
			
			$.post("MostrarRegistrosPendientes.php", {valorBusqueda: textoBusqueda, valorTipo: TipoBusqueda,valorSoloIncidencias: soloincidencias,valorIdUsuario: idusuario}, function(mensaje) 
					{
						$("#resultadoBusqueda").html(mensaje);
					}
				);  

		}

</script>

<?php 

function CargarDatonIniciales()
		{
			require_once '../../config/db.php';
			include_once('../Lib/FuncionesComunes.php');
			$data = data_submitted();
			//print_object($data);

			$RegistroTipo = $data->VarTipo;
			$UserId = $data->idusuario;
			if(isset($data->VarSoloIncidencias))
				{$SoloIncidencias = $data->VarSoloIncidencias;}
			else
				{$SoloIncidencias = 0;}

			if ($RegistroTipo==0)
				{
					echo "<input type='hidden' id='VarTipoValue' name='VarTipoValue' value='Todos'>";
				}
			else
				{
					$TipoValue = getDatoPorId("sds_reg_tipo", "idtipo", "descripcion", $RegistroTipo);
					echo "<input type='hidden' id='VarTipoValue' name='VarTipoValue' value='$TipoValue'>";
				}
			
			echo "<input type='hidden' id='VarSoloIncidencias' name='VarSoloIncidencias' value='$SoloIncidencias'>";
			echo "<input type='hidden' id='idusuario' name='idusuario' value='$UserId'>";
			echo "<input type='hidden' id='VarTipo' name='VarTipo' value='$RegistroTipo'>";

		}
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
				//echo "<input type='radio' id= 'RadioIncidencias' name='RadioTipo' value='Incidencias' onchange='buscar();'>Incidencias"; 
				
				while ($result = $dbh->Registro())
				{
					
					echo "<input type='radio' id= 'Radio".$result['descripcion']."' name='RadioTipo' value='".$result['descripcion']."' onchange='buscar();'>".$result['descripcion']; 

				}
			}

		$dbh->Cerrar();
		$dbh = NULL;
	}

?> 