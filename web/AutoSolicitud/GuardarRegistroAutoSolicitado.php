<?php
require_once 'Lib/db_01.php';
include_once('Lib/FuncionesComunes.php');


$data = data_submitted();
print_object($data);
//Cosas sin uso


	//$idderivador = $data->VarUsuarioOrigen;
	//$idderivador = getId('Usuarios', 'IdUsuario', 'Usuario', "$idderivador");
	//$IdTipoDeProblema = $data->VarIdTipoDeProblema;
	//$IdElementoProblematico = $data->VarIdElementoProblematico;
	//$Trabajo = $data->VarTrabajo;
	//$Autorizado = $data->VarAutorizado;
$idderivador = $data->VarIdUsuario;
$Fecha = $data->VarFecha;
$Hora = $data->VarHora;
$Sector = $data->VarIdArea;
$persona = $data->VarIdUsuario;
$Problema= $data->VarProblema;
$Tecnico = $data->VarTecnicos;
$RegistroAbierto = $data->VarRegistroAbierto;
$IpOrigen = $data->VarIpOrigen;
$Problema= "Origen $IpOrigen: $Problema";
$Telefono = $data->VarTelefono;

$dbh = new BaseDatos();
$dbh->Iniciar();

//Se fija que no haya cargadom un registro con un usuario y sector igual a los que se quieren cargar
$consulta = "Select * From RegistrosDiaADia Where Sector = $Sector and persona = $persona and RegistroAbierto = 1 and IncidenciaRelacionada IS NULL ";
$result = $dbh->Select($consulta); 

$IdRegistro = 0;

if(!$result)
	{echo "<p>Error en la consulta.</p>";}
else 
	{
		//Si existe un registro con sector y usuario ya cargados le asigna el id a $IdRegistro, sino queda en 0
		while ($result = $dbh->Registro())
				{
					$IdRegistro = $result['id'];
					$AuxProblema = $result['Problema'];
				}
	}


if ($IdRegistro==0)//Si no habia registro, lo carga
	{
		$consulta = "INSERT INTO RegistrosDiaADia (idderivador, Fecha, Hora, Sector, persona, Problema, Tecnico, IdTipoDeProblema, IdElementoProblematico, Trabajo, Autorizado, RegistroAbierto) VALUES($idderivador, '$Fecha', '$Hora', $Sector, $persona, '$Problema', '$Tecnico', 0, 0, '', 0, $RegistroAbierto)"; 
		$dbh->Ejecutar($consulta);
		echo "<script>alert('Solicitud cargada con exito!!')</script>";
	}
else//si existia, avisa que ya existe
	{
		$AuxUsuario = getDatoPorId('Usuarios', 'IdUsuario', 'Usuario', $persona);
		$AuxSector = getDatoPorId('Areas', 'IdArea', 'Area', $Sector);
		$Mensaje = 'Ya existe una solicitud realizada por el usuario:\n '.$AuxUsuario.' de '.$AuxSector.' \n\nDescribe el siguiente problema:\n'.$AuxProblema.' \n\n Por favor, aguarde la asistencia solicitada para realizar todas sus consultas';
		echo "<script>alert('$Mensaje')</script>";
	}


//Si el usuario puso un telefono actualiz
if(!$Telefono=="")
{
	echo "Telefono: $Telefono";
	$consulta = "Update Usuarios Set Interno = $Telefono Where idUsuario='$persona'";
	echo "<br>$consulta<br>";
	$dbh->Ejecutar($consulta);
}


$dbh->Cerrar();
$dbh = NULL;


header('Location: SolicitarAsistencia.php');



?>

