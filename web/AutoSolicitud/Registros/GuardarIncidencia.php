<?php
require_once '../../config/db.php';
include_once('../Lib/FuncionesComunes.php');

$data = data_submitted();
print_object($data);
//Variables para sds_reg_registro--------------------------------------------------------
$IdRegistro = $data->VarIdRegistro;
$Fecha = $data->VarFechaInicio;
$Hora = $data->VarHoraInicio;
$fecha_hora = "$Fecha $Hora";
$iddispositivo = $data->VarIdDispositivo;
$idorganismo = getId('mds_org_dispositivo', 'idorganismo', 'iddispositivo', "$iddispositivo");
$usuario_solicitante = $data->VarIdContactoInicio;
$problema= $data->varProblema;
$usuario_derivacion = $data->VarIdDerivador;
$equipo_detalle = $data->VarEquipo;
$ip = $data->VarIp;
$incidencia_relacionada = 1;
//Variables para Movimiento------------------------------------------------------------
$FechaMovimiento = $data->VarFechaMovimiento;
$movimiento_descripcion = $data->VarMovimientoDescripcion;
$tecnicos = $data->VarTecnicoMovimiento;
$tipo_movimiento = $data->VarTipoMovimiento;
$VarTipo = $data->VarTipo;

if($tipo_movimiento==2)//se finaliza la incidencia
	{
		$consulta = "Update sds_reg_registro Set incidencia_relacionada = $incidencia_relacionada, fecha_hora = '$fecha_hora', iddispositivo = '$iddispositivo', idorganismo ='$idorganismo', usuario_solicitante='$usuario_solicitante', problema = '$problema', equipo_detalle = '$equipo_detalle', ip = '$ip', registro_abierto = 0 Where idregistro=$IdRegistro";
	}
else
	{
		$consulta = "Update sds_reg_registro Set incidencia_relacionada = $incidencia_relacionada, fecha_hora = '$fecha_hora', iddispositivo = '$iddispositivo', idorganismo ='$idorganismo', usuario_solicitante='$usuario_solicitante', problema = '$problema', equipo_detalle = '$equipo_detalle', ip = '$ip' Where idregistro=$IdRegistro";
	}
	
echo "<br><br>$consulta<br><br>";

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;

GuardarMovimiento($IdRegistro,$FechaMovimiento,$movimiento_descripcion,$usuario_derivacion,$tecnicos,$tipo_movimiento);

if($tipo_movimiento==2)//se finaliza la incidencia
	{
		header('Location: RegistrosCerrados.php');
	}
else	
	{
		header('Location: RegistrosPendientes.php?idusuario='.$usuario_derivacion.'&VarTipo='.$VarTipo.'&VarSoloIncidencias=1');
	}

function GuardarMovimiento($IdRegistro,$fecha_trabajo,$movimiento_descripcion,$usuario_derivacion,$Tecnicos,$tipo_movimiento)
	{

		$Tecnicos=$Tecnicos."-";
		$Tecnicos = limpia_espacios($Tecnicos);
		$len = strlen($Tecnicos);
		echo "<br>";
		$tecnico="";
		for($i=0;$i<$len;$i++)
			{
				if($Tecnicos[$i]=='-')
					{
						$IdTecnico = getId('mds_seg_usuario', 'idusuario', 'user', "$tecnico");
						echo "Id: $IdTecnico User: $tecnico<br>";
						InsertarMovimiento($IdRegistro,$fecha_trabajo,$movimiento_descripcion,$usuario_derivacion,$IdTecnico,$tipo_movimiento);
						$tecnico ="";

					}
				else
					{
						$tecnico = $tecnico.$Tecnicos[$i];
					}
				
			}
	}
function InsertarMovimiento($IdRegistro,$fecha_trabajo,$movimiento_descripcion,$usuario_derivacion,$IdTecnico,$tipo_movimiento)
	{
		$consulta = "INSERT INTO sds_reg_movimiento (idregistro,fecha,descripcion,idusuario,idtecnico,tipo) VALUES($IdRegistro,'$fecha_trabajo','$movimiento_descripcion',$usuario_derivacion,$IdTecnico,$tipo_movimiento)";

		echo "<br><br>$consulta<br><br>";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$dbh->Ejecutar($consulta);
		$dbh->Cerrar();
		$dbh = NULL;
	}


?>


