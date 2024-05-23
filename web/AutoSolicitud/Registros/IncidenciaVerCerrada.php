<?php
	require_once '../Lib/db.php';
	include_once('../Lib/FuncionesComunes.php');
	VerificarSession();

	//$data = data_submitted();
	//print_object($data);

?>


<!doctype html>
<html>
  <head>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
  </head>
  <body onload="setcontroles()">
  <font size=3>

			*Direccion de Informatica * Servicio Tecnico * Incidencia
			<hr>
			
			<table id="TableIngreso"> 
				<table> 
					<tr> 
						<b>Ingreso de equipo:</b>
					</tr> 

					<tr style="border-bottom:1pt solid black;"> 
						<td>Equipo:</td>
						<td>
							<input type="text" id="InputEquipo" name="InputEquipo" style="width:300px;" readonly="true">
							Ip:
							<input type="text" id="InputIp" name="InputIp" readonly="true">	
							Fecha:
							<input type="text" id="InputFechaIngreso" name="InputFechaIngreso" readonly="true">
						</td>	
					</tr> 

					<tr> 
						<td>Sector:</td>
						<td><input id="InputSector" style="width:700px;" readonly="true"/></td>
					</tr> 

					<tr> 
						<td>Entrega: </td>
						<td><input id="InputUsuarioIngreso" readonly="true"/></td>
					</tr> 

					<tr> 
						<td>Recibe: </td>
						<td><input id="InputTecnicoIngreso" readonly="true" style="width:700px;"/></td>
					</tr> 
				</table> 
				<table> 
					<tr> 
						<td>Problema Reportado:</td>
					</tr> 
					<tr> 
						<td><textarea id="InputProblema" name="InputProblema" rows=3 readonly="true" style="width:800px;"/></textarea></td>
					</tr> 
				</table> 
			</table> 

			<hr>
			
			<table id="TableDesarrollo"> 
				<table> 
					<tr> 
						<b>Seguimiento del problema:</b>
					</tr> 
					<tr> 
						<td>Tecnico: </td>
						<td><input id="InputTecnicoDesarrollo" readonly="true" style="width:700px;"/></td>
					</tr> 
				</table> 
				<table> 
					<tr> 
						<td>Descripcion:</td>
					</tr> 
					<tr> 
						<td><textarea id="InputDesarrollo" name="InputDesarrollo" rows=3 readonly="true" style="width:800px;"/></textarea></td>
					</tr> 
					<tr> 
						<td>
							Fecha Solucion:
							<input type="text" id="InputFechaSolucion" name="InputFechaSolucion" readonly="true">
						</td>
					</tr> 
				</table> 
			</table> 

			<hr>
			
			<table id="TableEgreso"> 
				<table> 
					<tr> 
						<b>Egreso de equipo:</b>
					</tr> 

					<tr> 
						<td>Fecha Egreso:</td>
						<td><input type="text" id="InputFechaEgreso" name="InputFechaEgreso" readonly="true"></td>	
					</tr>

					<tr> 
						<td>Recibe: </td>
						<td><input id="InputUsuarioEgreso" readonly="true"/></td>
					</tr> 

					<tr> 
						<td>Despacha: </td>
						<td><input id="InputTecnicoEgreso" readonly="true" style="width:700px;"/></td>
					</tr> 
				</table> 
			</table> 

			<hr>

			
			<button type="button" name="Salir" onclick = "location='IncidenciasCerradas.php'">Salir</button>

  </font>
  </body>

</html>


<script type="text/javascript">

	
	function setcontroles()
		{
			var id = "";
			var dato ="";

			document.getElementById("InputEquipo").value = document.getElementById("VarEquipo").value;

			document.getElementById("InputIp").value = document.getElementById("varIp").value;

			document.getElementById("InputFechaIngreso").value = document.getElementById("varFechaIngreso").value;
			
			document.getElementById("InputSector").value = document.getElementById("VarSector").value;

			document.getElementById("InputUsuarioIngreso").value = document.getElementById("VarUsuarioIngreso").value;

			document.getElementById("InputTecnicoIngreso").value = document.getElementById("varTecnicoIngreso").value;
			
			document.getElementById("InputProblema").value = document.getElementById("varProblema").value;

			document.getElementById("InputTecnicoDesarrollo").value = document.getElementById("varTecnicoSeguimiento").value;

			document.getElementById("InputDesarrollo").value = document.getElementById("varSeguimiento").value;

			document.getElementById("InputFechaSolucion").value = document.getElementById("varFechaSeguimiento").value;

			document.getElementById("InputFechaEgreso").value = document.getElementById("varFechaEgreso").value;

			document.getElementById("InputUsuarioEgreso").value = document.getElementById("varUsuarioEgreso").value;

			document.getElementById("InputTecnicoEgreso").value = document.getElementById("varTecnicoEgreso").value;
	
		}


</script>


<?php 
	require_once '../Lib/FuncionesComunes.php';
	require_once '../Lib/db.php';

	AbrirIncidencia();

	function AbrirIncidencia() 
	{

		require_once '../Lib/db.php';
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$data = data_submitted();
		//print_object($data);

		$IdIncidencia = $data->varIncOperacion;

    	$consulta = "Select * FROM RegistrosDiaADiaIncidencias WHERE  RegistrosDiaADiaIncidencias.idIncidencia = ".$IdIncidencia;

		$AuxDato="";
		
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
				$AuxDato = $data->varIncOperacion;
				echo "<input type='hidden' id='VarOperacion' name='VarOperacion' value='$AuxDato'>";

				$AuxDato = $result["IdRegistroVinculado"];
				echo "<input type='hidden' id='VarIdRegistroVinculado' name='VarIdRegistroVinculado' value='$AuxDato'>";

				$AuxDato = $result["Equipo"];
				echo "<input type='hidden' id='VarEquipo' name='VarEquipo' value='$AuxDato'>";

				$AuxDato = $result["Ip"];
				echo "<input type='hidden' id='varIp' name='varIp' value='$AuxDato'>";

				$AuxDato = $result["FechaIngreso"];
				$AuxDato= date_format(date_create("$AuxDato"),"d/m/Y");
				echo "<input type='hidden' id='varFechaIngreso' name='varFechaIngreso' value='$AuxDato'>";	

				$AuxDato = $result["IdArea"];
				$AuxDato = getDatoPorId('Areas', 'IdArea', 'Area', $AuxDato);
				echo "<input type='hidden' id='VarSector' name='VarSector' value='$AuxDato'>";

				$AuxDato = $result["IdUsuarioIngreso"];
				$AuxDato = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $AuxDato);
				echo "<input type='hidden' id='VarUsuarioIngreso' name='VarUsuarioIngreso' value='$AuxDato'>";

				$AuxDato = $result["TecnicoIngreso"];
				echo "<input type='hidden' id='varTecnicoIngreso' name='varTecnicoIngreso' value='$AuxDato'>";

				$AuxDato = $result["Problema"];
				echo "<input type='hidden' id='varProblema' name='varProblema' value='$AuxDato'>";

				$AuxDato = $result["TecnicoSolucion"];
				echo "<input type='hidden' id='varTecnicoSeguimiento' name='varTecnicoSeguimiento' value='$AuxDato'>";

				$AuxDato = $result["Solucion"];
				echo "<input type='hidden' id='varSeguimiento' name='varSeguimiento' value='$AuxDato'>";

				$AuxDato = $result["FechaSolucion"];
				$AuxDato= date_format(date_create("$AuxDato"),"d/m/Y");
				echo "<input type='hidden' id='varFechaSeguimiento' name='varFechaSeguimiento' value='$AuxDato'>";

				$AuxDato = $result["FechaEgreso"];
				$AuxDato= date_format(date_create("$AuxDato"),"d/m/Y");
				echo "<input type='hidden' id='varFechaEgreso' name='varFechaEgreso' value='$AuxDato'>";
				
				$AuxDato = $result["IdUsuarioEgreso"];
				$AuxDato = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $AuxDato);
				echo "<input type='hidden' id='varUsuarioEgreso' name='varUsuarioEgreso' value='$AuxDato'>";
				
				$AuxDato = $result["TecnicoEgreso"];
				echo "<input type='hidden' id='varTecnicoEgreso' name='varTecnicoEgreso' value='$AuxDato'>";
			}	
		
		$dbh->Cerrar();
		$dbh = NULL;
		
	}

	
?>