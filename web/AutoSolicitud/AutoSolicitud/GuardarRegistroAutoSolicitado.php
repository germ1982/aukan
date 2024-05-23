<?php
require_once '../Lib/db.php';
include_once('../Lib/FuncionesComunes.php');


$data = data_submitted();
//print_object($data);

$idderivador = $data->VarIdUsuario;
$Fecha = $data->VarFecha;
$Hora = $data->VarHora;
$IdArea = $data->VarIdArea;
$IdUsuario = $data->VarIdUsuario;
$Problema= $data->VarProblema;
$Tecnico = $data->VarTecnicos;
$RegistroAbierto = $data->VarRegistroAbierto;
$IpOrigen = $data->VarIpOrigen;
$Problema= "Origen $IpOrigen: $Problema";
$Telefono = $data->VarTelefono;
$Fecha = str_replace("/", "-", $Fecha);
//echo"<br>$Fecha<br>";


$dbh = new BaseDatos();
$dbh->Iniciar();

//Se fija que no haya cargadom un registro con un usuario y Area igual a los que se quieren cargar
$consulta = "Select * From RegistrosDiaADia Where IdArea = $IdArea and IdUsuario = $IdUsuario and RegistroAbierto = 1 and IncidenciaRelacionada IS NULL ";
$result = $dbh->Select($consulta); 

$IdRegistro = 0;

if(!$result)
	{echo "<p>Error en la consulta.</p>";}
else 
	{
		//Si existe un registro con Area y usuario ya cargados le asigna el id a $IdRegistro, sino queda en 0
		while ($result = $dbh->Registro())
				{
					$IdRegistro = $result['IdRegistro'];
					$AuxProblema = $result['Problema'];
				}
	}


if ($IdRegistro==0)//Si no habia registro, lo carga
	{
		$consulta = "INSERT INTO RegistrosDiaADia (IdDerivador, Fecha, Hora, IdArea, IdUsuario, Problema, Tecnico, IdTipoDeProblema, IdElementoProblematico, Trabajo, Autorizado, RegistroAbierto, FechaTrabajo) VALUES($idderivador, STR_TO_DATE( '$Fecha', '%d-%m-%Y' ), '$Hora', $IdArea
	, $IdUsuario, '$Problema', '$Tecnico', 0, 0, '', 0, $RegistroAbierto, '0000-00-00')"; 
		//echo "<br>$consulta<br>";
		$dbh->Ejecutar($consulta);
		$Mensaje= 'Solicitud cargada con exito!!';
	}
else//si existia, avisa que ya existe
	{
		$AuxUsuario = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $IdUsuario);
		$AuxArea = getDatoPorId('Areas', 'IdArea', 'Area', $IdArea
		);
		$Mensaje = 'Ya existe una solicitud realizada por el usuario:\n '.$AuxUsuario.' de '.$AuxArea.' \n\nDescribe el siguiente problema:\n'.$AuxProblema.' \n\n Por favor, aguarde la asistencia solicitada para realizar todas sus consultas';
	}


//Si el usuario puso un telefono actualiz
if(!$Telefono=="")
{
	//echo "Telefono: $Telefono";
	$consulta = "Update Usuarios Set Telefono = $Telefono Where IdUsuario='$IdUsuario'";
	//echo "<br>$consulta<br>";
	$dbh->Ejecutar($consulta);
}


$dbh->Cerrar();
$dbh = NULL;
//echo $Mensaje;
echo "<script>alert('$Mensaje'); window.location.href='SolicitarAsistencia.php';</script>";
//header('Location: SolicitarAsistencia.php');



?>

