<?php
require_once '../../config/db.php';
include_once('../Lib/FuncionesComunes.php');

$data = data_submitted();
print_object($data);

$usuario_derivacion = $data->VarIdDerivador;
$Operacion = $data->VarOperacion;

$Fecha = $data->FechaActual;
$Hora = $data->HoraActual;
$fecha_hora = ArmarDateTimeParaMySql($Fecha, $Hora);

$iddispositivo = $data->VarIdDispositivo;
$idorganismo = getId('mds_org_dispositivo', 'idorganismo', 'iddispositivo', "$iddispositivo");

$usuario_solicitante = $data->VarIdUsuario;
$problema= $data->InputProblema;
$fecha_trabajo = ArmarDateParaMySql($data->FechaTrabajo);

$registro_abierto = $data->VarRegistroAbierto;
$incidencia_relacionada = $data->VarIncidencia;

$tipo = $data->VarRegistroIdTipo;

$movimiento_descripcion = $data->VarTrabajo;
$tecnicos = $data->VarTecnicos;

$tipo_movimiento = DefinirTipoMovimiento($incidencia_relacionada,$registro_abierto);

$otro_nuevo = $data->VarOtroNuevo;

if ($Operacion=='Nuevo')
	{
		$consulta = "INSERT INTO sds_reg_registro (fecha_hora, iddispositivo, idorganismo, usuario_solicitante, problema, usuario_derivacion, registro_abierto, incidencia_relacionada, idtipo) VALUES('$fecha_hora', $iddispositivo, $idorganismo, $usuario_solicitante, '$problema', $usuario_derivacion, $registro_abierto, 0, $tipo)"; 
		$ban='Nuevo';
	}
else
	{
		
		$IdRegistro = $data->VarIdRegistro;
		$consulta = "Update sds_reg_registro Set iddispositivo = '$iddispositivo', idorganismo ='$idorganismo', usuario_solicitante='$usuario_solicitante', problema = '$problema', registro_abierto = $registro_abierto, idtipo = $tipo, fecha_solucion = '$fecha_trabajo', incidencia_relacionada = 0 Where idregistro=$IdRegistro";
		$ban='Existente';
	}

echo "<br><br>$consulta<br><br>";

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;



if ($incidencia_relacionada==1)
	{$ban='Incidencia';}
	
echo "<br><br>ban: $ban<br><br>";
if ($ban == 'Nuevo')
	{
		if (!($tecnicos==""))
			{
				$IdRegistro = GetUltimoRegistro();
				echo"<br><br>ultimo registro ingresado: $IdRegistro<br><br>";
				GuardarMovimiento($IdRegistro,$fecha_trabajo,"Tecnico en derivacion para asistencia",$usuario_solicitante,$tecnicos,$tipo_movimiento);
			}

		if ($otro_nuevo==1)
			{
				header('Location: RegistroEditar.php?VarTipo=1&idusuario='.$usuario_derivacion);
			}
		else
			{
				header('Location: RegistrosPendientes.php?VarTipo='.$tipo.'&idusuario='.$usuario_derivacion);
			}	
	}

if ($ban == 'Existente')
	{
		GuardarMovimiento($IdRegistro,$fecha_trabajo,$movimiento_descripcion,$usuario_derivacion,$tecnicos,$tipo_movimiento);
		header('Location: RegistrosPendientes.php?VarTipo='.$tipo.'&idusuario='.$usuario_derivacion);
	}

if ($ban == 'Incidencia')
	{//esto ver de quitar y que solo sea incidencia si se guarda desde incidencia
		//GuardarMovimiento($IdRegistro,$fecha_trabajo,"Se deriva en incidencia",$usuario_derivacion,$tecnicos,$tipo_movimiento);
		header("Location: Incidencia.php?idregistro=$IdRegistro&idusuario=$usuario_derivacion");
	}


function GetUltimoRegistro()
	{
		$consulta = "select * from sds_reg_registro order by idregistro desc limit 1";
		$dbh = new BaseDatos();
		$dbh->Iniciar();
		$result = $dbh->Select($consulta);
		if (!$result) 
			{
				echo "<p>Error en la consulta.</p>"; 
			}
		else 
			{	
				$result = $dbh->Registro();
				$IdRegistro = $result['idregistro'];
			}	
		
		$dbh->Cerrar();
		$dbh = NULL;
		return $IdRegistro;
	}

function GuardarMovimiento($IdRegistro,$fecha_trabajo,$movimiento_descripcion,$usuario_derivacion,$Tecnicos,$tipo_movimiento)
	{
		$Tecnicos=$Tecnicos."-";
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

function DefinirTipoMovimiento($incidencia_relacionada,$registro_abierto)
	{
		$ban=0;
		if($incidencia_relacionada==0)
			{
				if($registro_abierto==0)
					{
						$ban=2;
					}
				else
					{
						$ban =0;
					}
			}
		else
			{
				$ban=1;
			}
		return $ban;
	}

?>


