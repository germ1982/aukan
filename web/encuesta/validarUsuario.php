<?php

$dirBase = "";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
require_once $dirBase . 'comun/clases/clase_pacientes.php';
if (isset($_REQUEST['web'])) {
    $usuario = $_REQUEST['usuario'];
    $password = sha1($_REQUEST['password']);    
} else {
    $postdata = file_get_contents("php://input");
    if (isset($postdata)) {
        $request = json_decode($postdata);
        $usuario = $request->usuario;
        $password = sha1($request->password);
    }
}
error_log("USUARIO " . $usuario);
error_log("PASSWORD " . $password);

$bd = new baseDatos();
$bd->Conectarse();
error_log("SELECT idpaciente,nombre,cambio_password FROM pacientes_temp WHERE mail = '$usuario' AND password='$password'");
$bd->select("SELECT idpaciente,nombre,cambio_password FROM pacientes_temp WHERE mail = '$usuario' AND password='$password'");
if ($bd->numero_filas() > 0) { //lo encontró
    $paciente = $bd->registro();
    //reviso si tiene que cambiar o no la contraseña
    if ($paciente['cambio_password'] == 0) {
        $datos = array(
            'result' => 'cambio',
            'idpaciente' => $paciente['idpaciente'],
            'nombre' => $paciente['nombre'],
        );
    } else {
        $datos = array(
            'result' => 'ok',
            'idpaciente' => $paciente['idpaciente'],
            'nombre' => $paciente['nombre'],
        );
    }
} else {
    $datos = array(
        'result' => 'no'
    );
}
echo json_encode($datos, JSON_FORCE_OBJECT);
?>
