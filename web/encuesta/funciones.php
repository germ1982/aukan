<?php

$dirBase = "";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
require_once $dirBase . 'comun/clases/clase_pacientes.php';
require_once $dirBase . 'comun/clases/clase_archivos.php';
require_once $dirBase . 'comun/clases/clase_profesionales.php';
require_once $dirBase . "comun/clases/clase_especialidades.php";
require_once $dirBase . 'comun/clases/clase_listar.php';
require_once $dirBase . 'comun/clases/clase_patron_iterator.php';
switch ($_REQUEST['tipo']):
    case 'turnos':
        $user = $_REQUEST['idpaciente'];
        devolverTurnos($user);
        break;
    case 'profesionales':
        devolverProfesionales();
        break;
    case 'perfil':
        devolverPerfil($_REQUEST['idpaciente']);
        break;
    case 'modificarPerfil':
        $user = $_REQUEST['idpaciente'];
        $direccion = $_REQUEST['direccion'];
        $telefono = $_REQUEST['telefono'];
        $mail = $_REQUEST['mail'];
        $ocupacion = $_REQUEST['ocupacion'];
        modificarPerfil($user,$direccion,$telefono,$mail,$ocupacion);
        break;
    case 'turnosDisponibles':
        devolverTurnosDisponibles($_REQUEST['idprofesional'], $_REQUEST['idpaciente']);
        break;
    case 'estudios':
        $user = $_REQUEST['idpaciente'];
        devolverEstudios($user);
        break;
    case 'laboratorios':
        $user = $_REQUEST['idpaciente'];
        devolverLaboratorios($user);
        break;
endswitch;

function devolverTurnos($user) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd->select("SELECT * FROM turnos_web WHERE idpaciente = $user AND (estado = 1 OR estado = 2) ORDER BY fecha DESC LIMIT 5");
    if ($bd->numero_filas() > 0) { //lo encontró
        $i = 0;
        $turnos = array();
        while ($t = $bd->registro()) {
            $prof = new clase_profesionales($t['idprofesional']);
            $ind['nombre'] = $prof->nombre();
            $ind['turno'] = $t;
            switch ($t['estado']) {
                case 1:
                    $estado = "SOLICITADO";
                    $clase_estado = "info";
                    $disabled = "";
                    break;
                case 2:
                    $estado = "CANCELADO";
                    $clase_estado = "default";
                    $disabled = "disabled";
                    break;
                case 3:
                    $estado = "ATENDIDO";
                    $clase_estado = "success";
                    $disabled = "disabled";
                    break;
            }
            $ind['estado'] = $estado;
            $ind['clase_estado'] = $clase_estado;
            $ind['disabled'] = $disabled;
            $turnos[] = $ind;
        }

        $datos = array(
            'result' => 'ok',
            'turnos' => $turnos
        );
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function devolverProfesionales() {
    $prof = new clase_profesionales();
    $prof->todos_profesionales_activado_ambulatorio();
    $profesionales = $prof->arreglo_todos_profesionales_activado_ambulatorio();
    $iterator = new clase_patron_iterator($profesionales);
    $profesional = [];
    while ($iterator->existeElementoSiguiente()) {
        $fila_prof = $iterator->elementoSiguiente();
        $especialidad = new clase_especialidades($fila_prof['idespecialidad']);
        $p['idprofesional'] = $fila_prof['idprofesional'];
        $p['nombre'] = $fila_prof['nombre'];
        $p['especialidad'] = $especialidad->nombre();
        $profesional[] = $p;
    }
    if (sizeof($profesional) > 0) {
        $datos = array(
            'result' => 'ok',
            'profesionales' => $profesional
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function modificarPerfil($idpaciente,$direccion,$telefono,$mail,$ocupacion){
    $bd = new baseDatos();
    $bd->Conectarse();
    if ($bd->select("UPDATE pacientes SET direccion = '$direccion',telefono_celular='$telefono',mail='$mail',ocupacion='$ocupacion' WHERE idpaciente = $idpaciente")){
        $datos = array(
            'result' => 'ok'
        );
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function devolverPerfil($idpaciente) {
    $pac = new clase_pacientes($idpaciente);
    if ($pac->idpaciente() != '') {
        $paciente = [];
        $paciente['nombre'] = $pac->nombre();
        $paciente['documento'] = $pac->documento();
        $paciente['fecha_nacimiento'] = $pac->fecha_nacimiento();
        $paciente['direccion'] = $pac->direccion();
        $paciente['telefono'] = $pac->telefono_celular();
        $paciente['mail'] = $pac->mail();
        $paciente['ocupacion'] = $pac->ocupacion();
        $datos = array(
            'result' => 'ok',
            'perfil' => $paciente
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function devolverTurnosDisponibles($idprofesional, $idpaciente) {
    $prof = new clase_profesionales($idprofesional);
    $fecha_desde = date('Y-m-d');
    $fecha_hasta = date("Y-m-d", strtotime("$fecha_desde +30 day"));

    $bd = new baseDatos();
    $bd->Conectarse();
    $bd->select("SELECT * FROM turnos WHERE idprofesional = $idprofesional AND (fecha BETWEEN '$fecha_desde' AND '$fecha_hasta') AND idpaciente = 0 AND (estado is null or estado != 'NO DAR') AND YEARWEEK(fecha) NOT IN (SELECT YEARWEEK(fecha) from turnos_web WHERE idprofesional = $idprofesional AND idpaciente = $idpaciente AND estado = 1)");
    if ($bd->numero_filas() > 0) {
        $turnos = array();
        while ($t = $bd->registro()) {
            $ind['nombre'] = $prof->nombre();
            $ind['idprofesional'] = $idprofesional;
            $ind['fecha'] = $t['fecha'];
            $ind['hora'] = $t['hora'];
            $turnos[] = $ind;
        }
        $datos = array(
            'result' => 'ok',
            'turnos' => $turnos
        );
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function devolverEstudios($idpaciente) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd->select("SELECT * FROM pedidos_estudio WHERE idpaciente=$idpaciente AND tipo = 2");
    $estudios = [];
    if ($bd->numero_filas() > 0) {
        while ($estudio = $bd->registro()) {
            $estudios[] = $estudio;
        }
        $datos = array(
            'result' => 'ok',
            'estudios' => $estudios
        );
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function devolverLaboratorios($idpaciente) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd->select("SELECT * FROM lab_solicitud_analisis WHERE cli_paciente_id=$idpaciente");
    $laboratorios = [];
    if ($bd->numero_filas() > 0) {
        while ($laboratorio = $bd->registro()) {
            $lab['laboratorio'] = $laboratorio;
            $bd1->select("SELECT * FROM archivos WHERE id_referencia=".$laboratorio['id']." AND tipo = 'laboratorio'");
            while ($archivo = $bd1->registro()){
                $lab['archivos'][] = $archivo;
            }
            $laboratorios[] = $lab;
        }
        $datos = array(
            'result' => 'ok',
            'laboratorios' => $laboratorios
        );
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

?>
