<?php

$dirBase = "../../../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
switch ($_REQUEST['funcion']):
    case 1:
        resultado_encuestas($_REQUEST['empresa'], $_REQUEST['fecha_desde'], $_REQUEST['fecha_hasta']);
        break;
    case 2:
        reporte_peso($_REQUEST['empresa'], $_REQUEST['fecha_desde'], $_REQUEST['fecha_hasta']);
        break;
    case 3:
        reporte_problemas($_REQUEST['empresa'], $_REQUEST['fecha_desde'], $_REQUEST['fecha_hasta']);
        break;
endswitch;

function resultado_encuestas($id_tipo_encuesta, $fecha_desde, $fecha_hasta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $and = "";
    $data = "";
    if ($fecha_desde != '')
        $and .= " AND fecha_creacion >= '$fecha_desde'";
    if ($fecha_hasta != '')
        $and .= " AND fecha_creacion <= '$fecha_hasta'";
    $bd->select("SELECT * FROM encuesta JOIN pacientes_temp ON (encuesta.idpaciente_temp = pacientes_temp.idpaciente) WHERE completa = 1 AND SUBSTR(mail,INSTR(mail,'@')+1) = '" . $id_tipo_encuesta . "' $and ");
    if ($bd->numero_filas() > 0) { //encontro encuestas
        while ($encuesta = $bd->registro()) {
            $id_encuesta = $encuesta['id'];
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
                    . "<th colspan='2'>Pregunta</th>"
                    . "<th colspan='2'>Respuesta</th>"
                    . "</tr>"
                    . "</thead><tbody>";
            $bd1->select("SELECT * FROM encuesta_resultado JOIN encuesta_preguntas ON (encuesta_resultado.id_pregunta = encuesta_preguntas.id) WHERE id_encuesta = $id_encuesta");
            while ($resultado = $bd1->registro()) {
                $data .= "<tr><td colspan='2'>" . $resultado['id_pregunta'] . " - " . $resultado['pregunta'] . "</td><td colspan='2'>" . $resultado['valor'] . "</td></tr>";
            }
            $data .= "<tr><td colspan='4'>&nbsp;</td></tr>";
            $data .= "</tbody></table>";
        }
    }

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=resultado_encuestas.xls");
    echo $data;
}

function reporte_peso($id_tipo_encuesta, $fecha_desde, $fecha_hasta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $and = "";
    $data = "";
    if ($fecha_desde != '')
        $and .= " AND fecha_creacion >= '$fecha_desde'";
    if ($fecha_hasta != '')
        $and .= " AND fecha_creacion <= '$fecha_hasta'";
    $bd->select("SELECT * FROM encuesta JOIN pacientes_temp ON (encuesta.idpaciente_temp = pacientes_temp.idpaciente) WHERE completa = 1 AND SUBSTR(mail,INSTR(mail,'@')+1) = '" . $id_tipo_encuesta . "' $and AND encuesta.idpaciente IS NOT NULL");
    if ($bd->numero_filas() > 0) { //encontro encuestas
        while ($encuesta = $bd->registro()) {
            $id_encuesta = $encuesta['id'];
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
                    . "<th colspan='2'>Peso</th>"
                    . "<th colspan='2'>IMC</th>"
                    . "<th colspan='2'>Circunsferencia Cintura</th>"
                    . "</tr>"
                    . "</thead><tbody>";
            $bd1->select("SELECT * FROM uc_evolucion_nutricion WHERE idpaciente = " . $encuesta['idpaciente'] . " ORDER BY fecha DESC LIMIT 1");
            $nutricion = $bd1->registro();
            $data .= "<tr><td colspan='2'>" . $nutricion['peso'] . "</td><td colspan='2'>" . $nutricion['imc'] . "</td><td colspan='2'>" . $nutricion['circunferencia_cintura'] . "</td></tr>";
            $data .= "<tr><td colspan='6'>&nbsp;</td></tr>";
            $data .= "</tbody></table>";
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
    if ($fecha_desde != '')
        $and .= " AND fecha_creacion >= '$fecha_desde'";
    if ($fecha_hasta != '')
        $and .= " AND fecha_creacion <= '$fecha_hasta'";
    $bd->select("SELECT * FROM encuesta JOIN pacientes_temp ON (encuesta.idpaciente_temp = pacientes_temp.idpaciente) WHERE completa = 1 AND SUBSTR(mail,INSTR(mail,'@')+1) = '" . $id_tipo_encuesta . "' $and AND encuesta.idpaciente IS NOT NULL");
    if ($bd->numero_filas() > 0) { //encontro encuestas
        while ($encuesta = $bd->registro()) {
            $id_encuesta = $encuesta['id'];
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
            $bd1->select("SELECT * FROM pacientes_problemas WHERE idpaciente = " . $encuesta['idpaciente']);
            while ($problemas = $bd1->registro()){
                $data .= "<tr><td colspan='2'>" . devolverFechaNormal($problemas['fecha']) . "</td><td colspan='2'>" . $problemas['estado'] . "</td><td colspan='2'>" . $problemas['texto_tesauro'] . "</td></tr>";
            }
            $data .= "<tr><td colspan='6'>&nbsp;</td></tr>";
            $data .= "</tbody></table>";
        }
    }

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=reportes_problemas.xls");
    echo $data;
}

?>
