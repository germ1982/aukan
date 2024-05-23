<?php

$dirBase = "../../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
switch ($_REQUEST['funcion']):
    case 1:
        resultado_encuestas($_REQUEST['empresa'],$_REQUEST['id_user'], $_REQUEST['fecha_desde'], $_REQUEST['fecha_hasta']);
        break;
   /* case 2:
        reporte_peso($_REQUEST['empresa'], $_REQUEST['fecha_desde'], $_REQUEST['fecha_hasta']);
        break;
    case 3:
        reporte_problemas($_REQUEST['empresa'], $_REQUEST['fecha_desde'], $_REQUEST['fecha_hasta']);
        break;*/
endswitch;

function resultado_encuestas($id_tipo_encuesta,$id_user, $fecha_desde, $fecha_hasta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd2 = new baseDatos();
    $bd2->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $and = "";
    $data = "";
    //tengo que revisar si muestro todas las encuestas o solo las que respondio el usuario
    $bd2->select("SELECT reportes_generales FROM mds_encuesta_usuario_tipo WHERE id_usuario = $id_user AND id_tipo = $id_tipo_encuesta");
    $user = $bd2->registro();
    
    $and_general = "";
    if ($user['reportes_generales'] == 0 || $user['reportes_generales'] == ''){ //filtro por usuario
        $and_general = " AND id_user = $id_user";
    }

    if ($fecha_desde != '')
        $and .= " AND fecha_creacion >= '$fecha_desde'";
    if ($fecha_hasta != '')
        $and .= " AND fecha_creacion <= '$fecha_hasta'";
    
    
    $bd1->select("SELECT * FROM mds_encuesta_pregunta WHERE id_tipo_encuesta = $id_tipo_encuesta");

    $data .= "<table class='table'>"
            . "<thead>"
            . "<tr>"
            . "<th>Usuario</th>";
    while ($resultado = $bd1->registro()) {
        $bd2->select("SELECT * FROM mds_encuesta_respuesta WHERE id_pregunta = " . $resultado['id_pregunta'] . " AND tipo = 'radio_varios'");
        if ($bd2->numero_filas() > 0) {
            $resulta2 = $bd2->registro();
            $data .= "<th>" . $resultado['pregunta'] . " (" . $resulta2['radio_desde'] . ": " . $resulta2['texto_previo_desde'] . " / " . $resulta2['radio_hasta'] . ": " . $resulta2['texto_posterior_hasta'] . ")</th>";
        } else {
            $bd2->select("SELECT * FROM mds_respuesta_respuesta WHERE id_pregunta = " . $resultado['id_pregunta'] . " AND tipo = 'radio_varios_imagen'");
            if ($bd2->numero_filas() > 0) {
                $resulta2 = $bd2->registro();
                $data .= "<th>" . $resultado['pregunta'] . " (De " . $resulta2['radio_desde'] . " a " . $resulta2['radio_hasta'] . ")</th>";
            } else {
                $data .= "<th>" . $resultado['pregunta'] . "</th>";
            }
        }
        $result[] = $resultado['id_pregunta'];
    }
    $data .= "</tr></thead><tbody>";
    $bd->select("SELECT * FROM mds_encuesta LEFT JOIN mds_seg_usuario ON (id_user = idusuario) WHERE completa = 1 AND id_tipo_encuesta = $id_tipo_encuesta $and_general $and ");
    if ($bd->numero_filas() > 0) { //encontro encuestas
        while ($encuesta = $bd->registro()) {
            $data .= "<tr><td>" . $encuesta['nombre']." ".$encuesta['apellido'] . "</td>";
            $id_encuesta = $encuesta['id_encuesta'];
            for ($i = 0; $i <= sizeof($result); $i++) {
                $bd1->select("SELECT * FROM mds_encuesta_resultado WHERE id_encuesta = $id_encuesta AND id_pregunta = $result[$i]");
                if ($bd1->numero_filas() > 0) {
                    $respuesta = $bd1->registro();
                    $data .= "<td>" . $respuesta['valor'] . "</td>";
                } else {
                    $data .= "<td>-</td>";
                }
            }
            $data .= "</tr>";
        }
    }
    $data .= "</tbody></table>";

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=resultado_encuestas.xls");
    echo $data;
}
/*
function reporte_peso($id_tipo_encuesta, $fecha_desde, $fecha_hasta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $and = "";
    $data = "";
    $bd1->select("SELECT encuesta.id_tipo_encuesta FROM encuesta JOIN pacientes_temp ON (encuesta.idpaciente_temp = pacientes_temp.idpaciente) WHERE completa = 1 AND SUBSTR(mail,INSTR(mail,'@')+1) = '" . $id_tipo_encuesta . "' LIMIT 1");
    $empresa = $bd1->registro();
    $id_empresa = $empresa['id_tipo_encuesta'];
    if ($id_empresa == 0){
       $and_empresa = " AND SUBSTR(mail,INSTR(mail,'@')+1) = '" . $id_tipo_encuesta . "'";
    }else{
       $and_empresa = " AND encuesta.id_tipo_encuesta = " . $id_tipo_encuesta . "'";

    }
    if ($fecha_desde != '')
        $and .= " AND fecha_creacion >= '$fecha_desde'";
    if ($fecha_hasta != '')
        $and .= " AND fecha_creacion <= '$fecha_hasta'";
    $bd->select("SELECT encuesta.idpaciente as paciente,encuesta.id,pacientes_temp.* FROM encuesta JOIN pacientes_temp ON (encuesta.idpaciente_temp = pacientes_temp.idpaciente) WHERE completa = 1 $and_empresa $and AND encuesta.idpaciente IS NOT NULL");
    if ($bd->numero_filas() > 0) { //encontro encuestas
        while ($encuesta = $bd->registro()) {
            $id_encuesta = $encuesta['id'];
            $bd1->select("SELECT * FROM uc_evolucion_nutricion WHERE idpaciente = " . $encuesta['paciente'] . " AND peso != '' AND imc != '' AND circunferencia_cintura != '' ORDER BY fecha DESC LIMIT 1");
            if ($bd1->numero_filas() > 0) {
                $data .= "<table class='table'>"
                        . "<thead>"
                        . "<tr>"
                        . "<th>Nombre y Apellido</th>"
                        . "<th>DNI</th>"
                        . "<th>Fecha Nacimiento</th>"
                        . "<th>Mail</th>"
                        . "</tr>"
                        . "</thead><tbody>";
                $data .= "<tr><td>" . $encuesta['nombre'] . "</td><td>" . $encuesta['documento'] . "</td><td>" . devolverFechaNormal($encuesta['fecha_nacimiento']) . "</td><td>" . $encuesta['mail'] . "</td></tr>";
                $data .= "<tr><td colspan='4'>&nbsp;</td></tr>";
                $data .= "</tbody></table>";
                $data .= "<table class='table'>"
                        . "<thead>"
                        . "<tr>"
                        . "<th colspan='2'>Fecha</th>"
                        . "<th colspan='2'>Peso</th>"
                        . "<th colspan='2'>IMC</th>"
                        . "<th colspan='2'>Circunsferencia Cintura</th>"
                        . "</tr>"
                        . "</thead><tbody>";
                $nutricion = $bd1->registro();
                $data .= "<tr><td colspan='2'>" . devolverFechaNormal($nutricion['fecha']) . "</td><td colspan='2'>" . $nutricion['peso'] . "</td><td colspan='2'>" . $nutricion['imc'] . "</td><td colspan='2'>" . $nutricion['circunferencia_cintura'] . "</td></tr>";
                $data .= "<tr><td colspan='6'>&nbsp;</td></tr>";
                $data .= "</tbody></table>";
            }
        }
    }

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=reporte_peso.xls");
    echo $data;
}

function reporte_problemas($id_tipo_encuesta, $fecha_desde, $fecha_hasta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $and = "";
    $data = "";
    $bd1->select("SELECT encuesta.id_tipo_encuesta FROM encuesta JOIN pacientes_temp ON (encuesta.idpaciente_temp = pacientes_temp.idpaciente) WHERE completa = 1 AND SUBSTR(mail,INSTR(mail,'@')+1) = '" . $id_tipo_encuesta . "' LIMIT 1");
    $empresa = $bd1->registro();
    $id_empresa = $empresa['id_tipo_encuesta'];
    if ($id_empresa == 0){
       $and_empresa = " AND SUBSTR(mail,INSTR(mail,'@')+1) = '" . $id_tipo_encuesta . "'";
    }else{
       $and_empresa = " AND encuesta.id_tipo_encuesta = " . $id_tipo_encuesta . "'";

    }
    if ($fecha_desde != '')
        $and .= " AND fecha_creacion >= '$fecha_desde'";
    if ($fecha_hasta != '')
        $and .= " AND fecha_creacion <= '$fecha_hasta'";
    $bd->select("SELECT encuesta.idpaciente as paciente,encuesta.id,pacientes_temp.* FROM encuesta JOIN pacientes_temp ON (encuesta.idpaciente_temp = pacientes_temp.idpaciente) WHERE completa = 1 AND $and_empresa $and AND encuesta.idpaciente IS NOT NULL");
    if ($bd->numero_filas() > 0) { //encontro encuestas
        while ($encuesta = $bd->registro()) {
            $id_encuesta = $encuesta['id'];
            $bd1->select("SELECT * FROM pacientes_problemas WHERE idpaciente = " . $encuesta['paciente']);
            if ($bd1->numero_filas() > 0) {
                $data .= "<table class='table'>"
                        . "<thead>"
                        . "<tr>"
                        . "<th>Nombre y Apellido</th>"
                        . "<th>DNI</th>"
                        . "<th>Fecha Nacimiento</th>"
                        . "<th>Mail</th>"
                        . "</tr>"
                        . "</thead><tbody>";
                $data .= "<tr><td>" . $encuesta['nombre'] . "</td><td>" . $encuesta['documento'] . "</td><td>" . devolverFechaNormal($encuesta['fecha_nacimiento']) . "</td><td>" . $encuesta['mail'] . "</td></tr>";
                $data .= "<tr><td colspan='4'>&nbsp;</td></tr>";
                $data .= "</tbody></table>";
                $data .= "<table class='table'>"
                        . "<thead>"
                        . "<tr>"
                        . "<th colspan='2'>Fecha</th>"
                        . "<th colspan='2'>Estado</th>"
                        . "<th colspan='2'>Problema</th>"
                        . "</tr>"
                        . "</thead><tbody>";
                while ($problemas = $bd1->registro()) {
                    $data .= "<tr><td colspan='2'>" . devolverFechaNormal($problemas['fecha']) . "</td><td colspan='2'>" . $problemas['estado'] . "</td><td colspan='2'>" . $problemas['texto_tesauro'] . "</td></tr>";
                }
                $data .= "<tr><td colspan='6'>&nbsp;</td></tr>";
                $data .= "</tbody></table>";
            }
        }
    }

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=reportes_problemas.xls");
    echo $data;
}
*/
?>
