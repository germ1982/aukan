<?php
require_once '../../config/db.php';
include_once('../Lib/FuncionesComunes.php');


$data = data_submitted();
print_object($data);

$Fecha = $data->VarFecha;
$Hora = '00:00:00';
$Fecha ="$Fecha $Hora";
$IdTecnico = $data->VarIdTecnico;
$Descripcion = $data->VarDescripcion;
$IdMovimiento = $data->VarIdMovimiento;


$consulta = "Update sds_reg_movimiento Set fecha ='$Fecha', idtecnico = $IdTecnico, descripcion = '$Descripcion' WHERE idmovimiento = $IdMovimiento";
echo $consulta;

$dbh = new BaseDatos();
$dbh->Iniciar();
$dbh->Ejecutar($consulta);
$dbh->Cerrar();
$dbh = NULL;

$RegistroIdTipo = $data->VarRegistroIdTipo;
$IdUsuario = $data->VarIdUsuario;
$IdRegistro = $data->VarIdRegistro;
$IncidenciaRelacionada = $data->VarIncidenciaRelacionada;
if ($IncidenciaRelacionada==1)
    {
        header('Location: incidencia.php?idusuario='.$IdUsuario.'&idregistro='.$IdRegistro);
    }
else
    {
        header('Location: RegistroEditar.php?VarTipo='.$RegistroIdTipo.'&idusuario='.$IdUsuario.'&VarIdRegistro='.$IdRegistro);
    }


?>
