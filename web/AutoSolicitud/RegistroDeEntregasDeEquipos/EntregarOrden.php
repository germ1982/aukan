<!doctype html>
<html lang="es">
  <head>
  	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    
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

			<datalist id="ListaUsuarios"> <?php GenerarListadoUsuarios(); ?> </datalist>
			
			<form id="FormEntregarOrden" method="post" action="GuardarEntregaDeOrden.php" onSubmit="return ValidarDatos();">
				<h1>Entregar Orden</h1>


				<button type="button" name="Cancelar" onclick = "EjecutarCancelar();" >Cancelar</button>
				<button type="submit">Guardar</button>

				<hr>
				<?php
					MostrarOrdenCompleta();
				?>
	 			<hr>
	 			<table>
	 				<tr>
		 				<td>Fecha de entrega de Insumos:</td>
						<td><input type="date" id="InputFecha"></td>	
	 				</tr>
					<tr>
			 			<td>Persona que entrega los insumos:</td>
			 			<td><input class="awesomplete" list="ListaUsuarios" id="InputListaUsuariosQueEntrega" placeholder="Usuario"/></td>
			 		</tr>
			 		<tr>	
			 			<td>Persona que retira Insumos:</td>
			 			<td><input class="awesomplete" list="ListaUsuarios" id="InputListaUsuariosQueRetira" placeholder="Usuario"/></td>	
	 				</tr>
				</table>
	 			<hr>

	 			<?php
					CrearVariablesHidden();
				?>
 			
			</form>

			

  </body>

</html>

<script type="text/javascript">
	MostrarFechaActual("InputFecha");


   	function ValidarDatos()   	
   		{

	   		var aux = document.getElementById("InputFecha").value;
	   		if (aux=="")
	   			{
	   				alert("Falta fecha de ingreso");
	   				return false;
	   			}


	   		var usuario = document.querySelector('#InputListaUsuariosQueEntrega').value;
		   		if (!ValidarDatoExistente("ListaUsuarios", usuario))
		   			{return false;}

		   	var Tecnico = document.querySelector('#InputListaUsuariosQueRetira').value;
		   		if (!ValidarDatoExistente("ListaUsuarios", Tecnico))
		   			{return false;}

	   		PrepararVariables();	
			return true;
		}


	function PrepararVariables()
		{

			var aux = document.getElementById("InputFecha").value;
			document.getElementById('VarFechaEgreso').value = aux;

			var aux = document.getElementById("InputListaUsuariosQueEntrega").value;
			document.getElementById('VarIdUsuarioEgreso').value = $('#ListaUsuarios [value="' + aux + '"]').data('idvalue');

			var aux = document.getElementById("InputListaUsuariosQueRetira").value;
			document.getElementById('VarIdUsuarioRecibeFinal').value = $('#ListaUsuarios [value="' + aux + '"]').data('idvalue');
		}


	function EjecutarCancelar()
		{
			var IdElemento = document.getElementById('VarIdOrdenDetalle').value;
			var aux = 'MenuOpcionesOrden.php?VarIdOrdenDetalle=' + IdElemento;
			location.href=aux;
		}
</script>

<?php  
	require_once '../Lib/FuncionesComunes.php';
	include_once 'db.php';

	function CrearVariablesHidden()
		{
			$data = data_submitted();
			//print_object($data);
			$IdOrdenDetalle = $data->VarIdOrdenDetalle;
			$IdOrden = getDatoPorId('VistaEntregasPendientes', 'IdOrdenDetalle', 'IdOrden', $IdOrdenDetalle);
			//echo "<br><br>IdOrdenDetalle: $IdOrdenDetalle<br>IdOrden: $IdOrden<br><br>";
			echo "<input type='hidden' id='VarIdOrdenDetalle' name='VarIdOrdenDetalle' value=$IdOrdenDetalle>";
			echo "<input type='hidden' id='VarIdOrden' name='VarIdOrden' value=$IdOrden>";
			echo "<input type='hidden' id='VarFechaEgreso' name='VarFechaEgreso'>";
			echo "<input type='hidden' id='VarIdUsuarioEgreso' name='VarIdUsuarioEgreso'>";
			echo "<input type='hidden' id='VarIdUsuarioRecibeFinal' name='VarIdUsuarioRecibeFinal'>";
		}

	function MostrarOrdenCompleta()
		{
			$data = data_submitted();
			//print_object($data);
			$IdOrdenDetalle = $data->VarIdOrdenDetalle;
			$IdOrden = getDatoPorId('VistaEntregasPendientes', 'IdOrdenDetalle', 'IdOrden', $IdOrdenDetalle);
			//echo "<br><br>IdOrdenDetalle: $IdOrdenDetalle<br>IdOrden: $IdOrden<br><br>";
			
			$consulta = "Select * from VistaEntregasPendientes where IdOrden = $IdOrden";
			//echo "$consulta";
			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($consulta);
			$result = $dbh->Registro();
			if (!$result) 
				{
					echo "<p>Error en la consulta.</p>"; 
				}
			else 
				{		
					echo "<br>Realizar entrega de Orden ".$result['Orden'];
					echo "<br>Perteneciente al expediente ".$result['Expediente']." iniciado en ".$result['Area'];
					echo "<br>Provedor ".$result['Provedor'];
					echo " recibido por ".$result['Usuario']." el dia ".$result['Fecha'];
					echo "<br>Insumos:<br>";
					MostrarInsumos($IdOrden);
				}
			echo "<br>";
			
			$dbh->Cerrar();
			$dbh = NULL;
		}

	function MostrarInsumos($IdOrden)
		{
			$consulta = "Select * from VistaEntregasPendientes where IdOrden = $IdOrden";
			$dbh = new BaseDatos();
			$dbh->Iniciar();
			$result = $dbh->Select($consulta);
			while ($result = $dbh->Registro())
				{
						echo $result['Insumos']."<br>"; 
				}
			$dbh->Cerrar();
			$dbh = NULL;
		}

	function GenerarListadoUsuarios() 
		{
			$IdArea = getId('Areas', 'IdArea', 'Area','Baja');
			$Consulta = "Select * from Usuarios Where not IdArea = $IdArea Order by Usuario";	
			GenerarListadoGenerico($Consulta, "IdUsuario", "Usuario", "IdArea");	
		}

?>