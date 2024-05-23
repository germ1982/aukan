<!doctype html>
<html>
  <head>
    <script type="text/javascript" src="../Lib/jquery-2.0.3.min.js"></script>   
    <script type="text/javascript" src="../Lib/FuncionesComunes.js"></script>   
	<link href="../Css/Registros.css" rel="stylesheet" type="text/css">
  </head>
  <body onload="setcontroles()">

			<?php MostrarTitulo()?>
			<hr>
			<?php MostrarDatosIniciantes()?>
			<hr>
			<b>Movimientos:</b>
					<?php 
						include_once('../Lib/FuncionesComunes.php');
			
						$data = data_submitted();
						//print_object($data);
			
						if(isset($data->VarIdRegistro))
							{
								MostrarMovimientos($data->VarIdRegistro,0,2);
							}
					?>
			<hr>
			<br>
			
			<button type="button" name="Salir" onclick = "location='RegistrosCerrados.php'">Volver</button>
		</form>
  </body>

</html>


<script type="text/javascript">
	
function setcontroles()
	{
/* 		var Fecha = document.getElementById("VarFecha").value;
		var Hora = document.getElementById("VarHora").value;
		document.getElementById("InputFechaRegistro").value = Fecha + ' ' + Hora;
			document.getElementById("InputHoraRegistro").value = document.getElementById("VarHora").value;
			document.getElementById("InputIniciante").value = document.getElementById("VarIdUsuario").value;
			document.getElementById("InputArea").value = document.getElementById("VarIdArea").value;
			document.getElementById("InputProblema").value = document.getElementById("VarProblema").value;
			document.getElementById("InputTecnico").value = document.getElementById("VarTecnicos").value;
			document.getElementById("InputTipoDeProblema").value = document.getElementById("VarIdTipoDeProblema").value;
			document.getElementById("InputElementoProblematico").value = document.getElementById("VarIdElementoProblematico").value;
			document.getElementById("InputTrabajo").value = document.getElementById("VarTrabajo").value;	 */	
	}

</script>


<?php 
require_once '../../config/db.php';
include_once '../Lib/FuncionesComunes.php';

$data = data_submitted();
//print_object($data);
$IdRegistro = $data->VarIdRegistro;

$consulta = "SELECT * FROM sds_reg_registro WHERE idregistro = $IdRegistro";

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
		echo "<br><input type='hidden' id='VarIdRegistro' name='VarIdRegistro' value='$IdRegistro'><br>";

		

	}	

$dbh->Cerrar();
$dbh = NULL;

function MostrarDatosIniciantes()
	{
		require_once '../../config/db.php';
		include_once '../Lib/FuncionesComunes.php';

		$data = data_submitted();
		//print_object($data);
		$IdRegistro = $data->VarIdRegistro;

		$consulta = "SELECT * FROM sds_reg_registro WHERE idregistro = $IdRegistro";

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
				$date = date_create($result['fecha_hora']);
				$Fecha=date_format($date, 'd/m/Y');
				$Hora=date_format($date, 'H:m');
				$Contacto = GetContactoValue($result["usuario_solicitante"]);
				$Dispositivo = getDatoPorId("mds_org_dispositivo", "iddispositivo", "descripcion", $result["iddispositivo"]);
				$Organismo = getDatoPorId("mds_org_organismo", "idorganismo", "descripcion", $result["idorganismo"]);
				$Problema = $result["problema"];
				echo "<b>Iniciante:</b> $Contacto<br> <b>Dispositivo:</b> $Dispositivo <br> <b>Organismo:</b> $Organismo";
				echo "<br><b>Problema reportado:</b> $Problema<br> <b>Fecha:</b> $Fecha <br> <b>Hora:</b> $Hora";

			}	

		$dbh->Cerrar();
		$dbh = NULL;
	}

function  MostrarTitulo()
	{
		require_once '../../config/db.php';
		include_once '../Lib/FuncionesComunes.php';

		$data = data_submitted();
		//print_object($data);
		$IdRegistro = $data->VarIdRegistro;

		$consulta = "SELECT * FROM sds_reg_registro WHERE idregistro = $IdRegistro";

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

				if($result["incidencia_relacionada"]==1)
					{
						echo"<h2>Incidencia numero: $IdRegistro</h2><hr style='font-size:20px'> ";
						$Equipo = $result["equipo_detalle"];
						$Ip = $result["ip"];
						echo "<b>Equipo:</b> $Equipo <b>Ip:</b> $Ip <br>";
					}
				else
					{
						$TipoRegistro = getDatoPorId("sds_reg_tipo", "idtipo", "descripcion", $result["idtipo"]);
						echo"<h2>Registro numero: $IdRegistro, de tipo $TipoRegistro</h2>";
					}

			}	

		$dbh->Cerrar();
		$dbh = NULL;
	}

?>