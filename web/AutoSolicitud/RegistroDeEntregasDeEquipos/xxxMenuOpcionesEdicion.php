<!doctype html>
<html>
  <head>
    <link href="../Css/Registros.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
  </head>

  <body>
	<?php 
		require_once '../Lib/FuncionesComunes.php';
		VerificarSession();
	?>
	

		<div id="Contenedor" style="width: 400px; text-align: center;">
			<h1>Opciones de Edicion</h1>
		
			<?php MostrarUsando();?>

			<hr>

			<?php CargarOpciones();?>

			<hr>	

			<button type="button" onclick = "location='EquiposPendientesAEntregar.php'">Volver</button>
		</div>
			

  </body>

</html>

<script>

function EjecutarOperacion(Operacion, IdOrdenDetalle)
	{
		switch(Operacion) 
			{
			    case 1:
			        aux = 'AñadirOrden.php?VarIdOrdenDetalle=' + IdOrdenDetalle;
			        break;
			    case 2:
			        aux = 'EditarNumeroExpediente.php?VarIdOrdenDetalle=' + IdOrdenDetalle;
			        break;
			    case 3:
			        aux = 'EditarUsuarioExpediente.php?VarIdOrdenDetalle=' + IdOrdenDetalle;
			        break;
			    case 4:
			        aux = 'EditarAreaExpediente.php?VarIdOrdenDetalle=' + IdOrdenDetalle;
			        break;
			    case 5:
			        aux = 'EntregarOrden.php?VarIdOrdenDetalle=' + IdOrdenDetalle;
			        break;
			    case 6:
			        alert("Falta");
			        break;
			    case 7:
			        aux = 'EditarNumeroOrden.php?VarIdOrdenDetalle=' + IdOrdenDetalle;
			        break;
			    case 8:
			        alert("Falta");
			        break;
			    default:
			        alert("Falta");
			}

		location.href=aux;
		return false;	
	}


</script>

<?php 
include_once 'db.php';
include_once '../Lib/FuncionesComunes.php';
header( 'Content-type: text/html; charset=utf8' );//esto para que no muestre caracteres raros

function CargarOpciones()
	{
		$data = data_submitted();
		//print_object($data);
		$IdOrdenDetalle = $data->VarIdOrdenDetalle;	
		$AuxDato="";
		$consulta = "Select * From VistaEntregasPendientes where IdOrdenDetalle = $IdOrdenDetalle";
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

				MostrarMenuExpediente($result["IdOrdenDetalle"], $result["Expediente"]);
				echo "<br>";
				MostrarMenuOrden($result["IdOrdenDetalle"], $result["Orden"]);
				echo "<br>";
				MostrarMenuDetalle($result["IdOrdenDetalle"], $result["Insumos"]);
			}	
		
		$dbh->Cerrar();
		$dbh = NULL;
	}

function MostrarMenuExpediente($IdOrdenDetalle, $Expediente)
	{
		echo"<div id='Caja' style='width: 400px; text-align: center;'>";
			echo "<table id='TablaRegistros' style='width: 400px; text-align: center;'>";
				
				echo "<tr>"; 
				echo "<th>Expediente $Expediente</th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='button' name='AñadirOrden' onclick='EjecutarOperacion(1,$IdOrdenDetalle);'>Añadir Orden</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarNumeroExpediente' onclick='EjecutarOperacion(2,$IdOrdenDetalle);'>Modificar Numero</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarUsuario' onclick='EjecutarOperacion(3,$IdOrdenDetalle);'>Modificar Usuario</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarArea' onclick='EjecutarOperacion(4,$IdOrdenDetalle);'>Modificar Area</button></th>";
				echo "</tr>";

			echo "</table>";
		echo "</div>";
	}

function MostrarMenuOrden($IdOrdenDetalle, $Orden)
	{
		echo"<div id='Caja' style='width: 400px; text-align: center;'>";
			echo "<table id='TablaRegistros' style='width: 400px; text-align: center;'>";
				
				echo "<tr>"; 
				echo "<th>Orden $Orden</th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='RealizarEntregaOrden' onclick='EjecutarOperacion(5,$IdOrdenDetalle);'>Realizar Entrega</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarExpediente' onclick='EjecutarOperacion(6,$IdOrdenDetalle);'>Modificar Expediente</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarNumeroOrden' onclick='EjecutarOperacion(7,$IdOrdenDetalle);'>Modificar Numero</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarFechaIngreso' onclick='EjecutarOperacion(8,$IdOrdenDetalle);'>Modificar Fecha De Ingreso</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarPRovedor' onclick='EjecutarOperacion(9,$IdOrdenDetalle);'>Modificar Provedor</button></th>";
				echo "</tr>";

			echo "</table>";
		echo "</div>";
	}

function MostrarMenuDetalle($IdOrdenDetalle, $Insumo)
	{
		echo"<div id='Caja' style='width: 400px; text-align: center;'>";
			echo "<table id='TablaRegistros' style='width: 400px; text-align: center;'>";
				
				echo "<tr>"; 
				echo "<th>Insumo: $Insumo</th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='QuitarDetalleOrden' onclick='EjecutarOperacion(10,$IdOrdenDetalle);'>Quitar</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarCantidadInsumo' onclick='EjecutarOperacion(11,$IdOrdenDetalle);'>Modificar Cantidad</button></th>";
				echo "</tr>";

				echo "<tr>"; 
				echo "<th><button type='submit' name='ModificarInsumo' onclick='EjecutarOperacion(12, $IdOrdenDetalle);'>Modificar Insumo</button></th>";
				echo "</tr>";

			echo "</table>";
		echo "</div>";
	}

function MostrarUsando()
	{
		$Usuario = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $_SESSION['gIdUsuario']);
		echo("Usando: $Usuario");
	}


?>