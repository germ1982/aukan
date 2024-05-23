<?php

$dirBase="";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
require_once $dirBase . 'comun/clases/clase_pacientes.php';
$password = sha1($_REQUEST['password']);
$idpaciente = $_REQUEST['user'];
$paciente = new clase_pacientes($idpaciente);
$dni = $_REQUEST['dni'];
$fecha_nacimiento = fechaBase($_REQUEST['fecha_nacimiento']);
$password_ant = $paciente->password();
$bd = new baseDatos();
$bd->Conectarse();

if ($password == $password_ant){
    $datos = array(
        'result' => 'igual'
    );
}else{
    syslog(LOG_NOTICE,"UPDATE pacientes_temp SET password='$password',cambio_password=1,documento='$dni',fecha_nacimiento='$fecha_nacimiento' WHERE idpaciente = $idpaciente");
    if ($bd->select("UPDATE pacientes_temp SET password='$password',cambio_password=1,documento='$dni',fecha_nacimiento='$fecha_nacimiento' WHERE idpaciente = $idpaciente")){
        $datos = array(
            'result' => 'ok',
            'idpaciente' => $idpaciente
        );
    }else{
        $datos = array(
            'result' => 'no'
        );
    }
}
echo json_encode($datos, JSON_FORCE_OBJECT);

?>
