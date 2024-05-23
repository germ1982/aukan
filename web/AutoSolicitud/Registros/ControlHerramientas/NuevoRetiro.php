<!doctype html>
<html>
  <head>
    <link href="../../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <link href="../../Css/Awesomplete.css" rel="stylesheet" type="text/css"/>
    <script src="../../Lib/awesomplete.min.js"></script>
    <script type="text/javascript" src="../../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../../Lib/FuncionesComunes.js"></script>   
  </head>
  <body onload="setcontroles()">

			<?php 
				require_once '../../Lib/FuncionesComunes.php'; 
				VerificarSession();
			?>

			<h1>Nuevo Retiro de Herramientas</h1>
			<hr>
				<table>
					<tr>
						<td>Persona que Retira:</td>
						<td><?php CargarComboTecnicos(); ?></td>	
					</tr>

					<tr>
						<td>Herramientas:</td>
						<td><textarea id="InputHerramientas" name="InputHerramientas" rows=3 placeholder="Herramientas"/></textarea></td>	
					</tr>

					<tr>
						<td>Tipo de Uso:</td>
						<td><?php CargarComboUsos(); ?></td>

					</tr>

					<tr>
						<td>Fecha Retiro:</td>
						<td><input type="date" id="FechaRetiro" name="FechaRetiro"></td>	
					</tr>
				</table>

			<form id="FormNuevoRetiro" method="post" action="GuardarNuevoRetiro.php" onSubmit="return ValidarDatos();">		
				<button type="submit" name="Guardar">Guardar</button>
				<button type="button" name="Cancelar" onclick = "location.href='ControlHerramientas.php';">Cancelar</button>
				<?php SetearVariables(); ?>
			</form>

  </body>

</html>


<script type="text/javascript">

   	function ValidarDatos()   	
   		{

	   		var Herramientas = document.querySelector('#InputHerramientas').value;
		   		if (Herramientas=="")
		   			{
		   				alert("Detalle las herramientas");
		   				return false;
		   			}			

	   		PrepararVariables();	

		}

	function PrepararVariables() 
		{

			var aux = document.getElementById("ComboTecnicos").value;
			document.getElementById('VarIdTecnico').value = aux;

			var aux = document.getElementById("InputHerramientas").value;
			document.getElementById('VarHerramientas').value = aux;

			var aux = document.getElementById("ComboUsos").value;
			document.getElementById('VarTipoUso').value = aux;

			var aux = document.getElementById('FechaRetiro').value;
			document.getElementById('VarFechaRetiro').value = aux;
		}

	function setcontroles()
		{
			MostrarFechaActual("FechaRetiro");
		}

</script>


<?php 

	function SetearVariables()
		{
			$IdUsuarioLogueado = $_SESSION['gIdUsuario'];
			echo "<input type='hidden' id='VarIdUsuarioLogueado' name='VarIdUsuarioLogueado' value='$IdUsuarioLogueado'>";
			echo "<input type='hidden' id='VarIdTecnico' name='VarIdTecnico'>";
			echo "<input type='hidden' id='VarHerramientas' name='VarHerramientas'>";
			echo "<input type='hidden' id='VarTipoUso' name='VarTipoUso'>";
			echo "<input type='hidden' id='VarFechaRetiro' name='VarFechaRetiro'>";
		}

	function CargarComboTecnicos()
		{
			$IdAplicacion = getId('Aplicaciones','IdAplicacion','Aplicacion','Registros Tecnicos');
			$consulta = "select * from Usuarios inner join AplicacionesPermisos on Usuarios.IdUsuario = AplicacionesPermisos.IdUsuario WHERE IdAplicacion = $IdAplicacion order by Usuario";

			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($consulta);
			$i=0;
			if (!$result) 
				{
					echo "<p>Error en la consulta.</p>"; 
				}
			else 
				{
					echo"<select id='ComboTecnicos'>";
						while ($result = $dbh->Registro())
							{			
									$Tecnico = $result["Usuario"];
									$IdTecnico = $result["IdUsuario"];
									echo"<option value='$IdTecnico'>$Tecnico</option>";
							}
					echo '</select>';
				}	
			
			$dbh->Cerrar();
			$dbh = NULL;
		}

function CargarComboUsos()
	{
		echo"<select id='ComboUsos'>";
			echo"<option value='1'>Laboral</option>";
			echo"<option value='2'>Particular</option>";
		echo"</select>";
	}

?>