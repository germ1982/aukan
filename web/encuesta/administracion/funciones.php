<?php

$dirBase = "../";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
if (isset($_REQUEST['funcion'])) {
    $opcion = $_REQUEST['funcion'];
} else {
    $opcion = null;
}
switch ($opcion):
    case 'traerEncuestas':
        traerEncuestas();
        break;
    case 'traerMisEncuestas':
        traerMisEncuestas($_REQUEST['id_user']);
        break;
    case 'traerMisEncuestasBusqueda':
        traerMisEncuestasBusqueda($_REQUEST['id_user'],$_REQUEST['id_tipo_encuesta']);
        break;
    case 'traerMisEncuestasBusquedaExterna':
        traerMisEncuestasBusquedaExterna($_REQUEST['id_user'],$_REQUEST['id_tipo_encuesta']);
        break;
    case 'guardarEncuesta':
        guardarEncuesta($_REQUEST['id_user'], $_REQUEST['id_tipo_encuesta'], $_REQUEST['fecha_creacion']);
        break;
    case 'eliminarEncuesta':
        eliminarEncuesta($_REQUEST['id_tipo_encuesta']);
        break;
    case 'eliminarResultadoEncuesta':
        eliminarResultadoEncuesta($_REQUEST['id_user'],$_REQUEST['id_encuesta']);
        break;
    case 'eliminarTipoEncuesta':
        eliminarTipoEncuesta($_REQUEST['id_tipo_encuesta']);
        break;
    case 'clonarResultadoEncuesta':
        clonarResultadoEncuesta($_REQUEST['id_user'],$_REQUEST['id_encuesta'],$_REQUEST['id_tipo_encuesta']);
        break;
    case 'clonarEncuesta':
        clonarEncuesta($_REQUEST['id_tipo_encuesta'], $_REQUEST['id_tipo_encuesta_new']);
        break;
    case 'traerAsignaciones':
        traerAsignaciones($_REQUEST['id_tipo_encuesta']);
        break;
    case 'guardarAsignacion':
        guardarAsignacion($_REQUEST['id'], $_REQUEST['id_tipo_encuesta'], $_REQUEST['respuesta_multiple'], $_REQUEST['respuesta_general'],$_REQUEST['reportes_generales'], $_REQUEST['users']);
        break;
    case 'borrarAsignacion':
        borrarAsignacion($_REQUEST['id']);
        break;
    case 'traerSecciones':
        traerSecciones($_REQUEST['id_tipo_encuesta']);
        break;
    case 'guardarSeccion':
        guardarSeccion($_REQUEST['id'], $_REQUEST['seccion'], $_REQUEST['explicacion'], $_REQUEST['orden'], $_REQUEST['id_tipo_encuesta']);
        break;
    case 'borrarSeccion':
        borrarSeccion($_REQUEST['id']);
        break;
    case 'traerPreguntas':
        traerPreguntas($_REQUEST['id'], $_REQUEST['id_tipo_encuesta']);
        break;
    case 'guardarPregunta':
        guardarPregunta($_REQUEST['id'], $_REQUEST['pregunta'], $_REQUEST['requerida'], $_REQUEST['encabezado'], $_REQUEST['orden'], $_REQUEST['busqueda'], $_REQUEST['id_seccion'], $_REQUEST['id_tipo_encuesta']);
        break;
    case 'borrarPregunta':
        borrarPregunta($_REQUEST['id']);
        break;
    case 'traerRespuestas':
        traerRespuestas($_REQUEST['id']);
        break;
    case 'guardarRespuesta':
        guardarRespuesta($_REQUEST['id'], $_REQUEST['id_pregunta'], $_REQUEST['tipo'], $_REQUEST['orden']);
        break;
    case 'guardarRespuestaNew':
        guardarRespuestaNew($_REQUEST['id_pregunta'], $_REQUEST['tipo'], $_REQUEST['orden']);
        break;
    case 'borrarRespuesta':
        borrarRespuesta($_REQUEST['id']);
        break;
    case 'traerTipoEncuesta':
        traerTipoEncuesta();
        break;
    case 'guardarTipoEncuesta':
        guardarTipoEncuesta($_REQUEST['id'], $_REQUEST['tipo_encuesta'], $_REQUEST['descripcion'],$_REQUEST['texto_inicial'],$_REQUEST['texto_final'], $_REQUEST['id_user']);
        break;
    case 'guardarFamiliasolidaria':
        guardarFamiliasolidaria($_REQUEST['nombre_apellido'], $_REQUEST['dni'], $_REQUEST['fecha_nacimiento'], $_REQUEST['edad'], $_REQUEST['telefono'], $_REQUEST['mail'], $_REQUEST['localidad']);
        break;
endswitch;

function traerEncuestas() {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd->select("SELECT * FROM mds_encuesta_tipo WHERE baja_fecha IS NULL");
    if ($bd->numero_filas() > 0) { //lo encontró
        $data = "<table class='table'>"
                . "<thead>"
                . "<tr>"
                . "<th>Tipo Encuesta</th>"
                . "<th>Descripcion</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead><tbody>";
        while ($tipo_encuesta = $bd->registro()) {
            $id_user = ($tipo_encuesta['id_user'] != null) ? $tipo_encuesta['id_user'] : 0;
            $data .= "<tr><td>"
                    . $tipo_encuesta['nombre']
                    . "</td><td>"
                    . $tipo_encuesta['descripcion']
                    . "</td><td>
                 <button class='btn btn-primary' title='Editar Tipo Encuesta' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Tipo de Encuesta'  data-target='#encuesta_modal' 
            href='modalCrearEncuesta.php?id_tipo_encuesta=" . $tipo_encuesta['id_tipo'] . "&id_user=" . $id_user . "'><i class='fa fa-edit'></i>
            </button>
                 <button class='btn btn-info' title='Agregar Encabezado y Pie de Página' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Tipo de Encuesta'  data-target='#encuesta_modal' 
            href='modalCustom.php?id_tipo_encuesta=" . $tipo_encuesta['id_tipo'] . "&id_user=" . $id_user . "'><i class='fa fa-cog'></i>
            </button>
            <button class='btn btn-success' title='Administrar Secciones' onclick='administrarSecciones("
                    . $tipo_encuesta['id_tipo'] . ");'><i class='fa fa-server'></i></button>&nbsp;
            <button class='btn btn-warning' title='Clonar Encuesta' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Clonar Encuesta' data-target='#encuesta_modal' href='modalClonar.php?id_tipo_encuesta="
                    . $tipo_encuesta['id_tipo'] . "'><i class='fa fa-copy'></i></button>
            &nbsp;<button class='btn btn-danger' title='Eliminar Encuesta' onclick='if (confirm(\"Está seguro de eliminar la encuesta?\")){ eliminarEncuesta("
                    . $tipo_encuesta['id_tipo'] . "); }'><i class='fa fa-remove'></i></button></td></tr>"
                    . "<tr class='info'><td colspan='4' id='secciones_" . $tipo_encuesta['id_tipo'] . "'></td></tr>";
        }
        $data .= "</tbody></table>";
        $datos = array(
            'result' => 'ok',
            'data' => $data
        );
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function traerMisEncuestas($id_user) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    //busco las encuestas que tiene asignadas, las muestro y voy a poner el boton para que pueda buscar en otra pagina cuales son las que va a ver y editar (las que no esten finalizadas)
    $bd->select("SELECT mds_encuesta_usuario_tipo.*,mds_encuesta_tipo.*,mds_seg_usuario.nombre as name_user,mds_seg_usuario.apellido,mds_seg_usuario.externo FROM mds_encuesta_usuario_tipo JOIN mds_encuesta_tipo USING (id_tipo) JOIN mds_seg_usuario ON (id_usuario = idusuario) WHERE id_usuario = $id_user AND mds_encuesta_usuario_tipo.baja_fecha IS NULL");
    if ($bd->numero_filas() > 0) { //lo encontró
        $data = "<table class='table'>"
                . "<thead>"
                . "<tr>"
                . "<th>Tipo Encuesta</th>"
                . "<th>Descripcion</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead><tbody>";
        while ($tipo_encuesta = $bd->registro()) {
            $mostrar = 1;
            //reviso si puede o no responder multiple
           /* $respuesta_multiple = $tipo_encuesta['respuesta_multiple'];
            if ($respuesta_multiple == 0){ //tengo que revisar si ya respondio alguna
                $bd1->select("SELECT * FROM mds_encuesta WHERE id_user = $id_user AND completa = 1");
                if ($bd1->numero_filas() > 0)
                    $mostrar = 0;
            }
            if ($mostrar){*/
                if ($tipo_encuesta['externo'] == 0)
                    $url = 'mis_encuestas_busqueda.php?id_user='.$id_user.'&name_user='.$tipo_encuesta['name_user'].'_'.$tipo_encuesta['apellido'].'&id_tipo_encuesta='.$tipo_encuesta['id_tipo'];
                else
                    $url = 'mis_encuestas_busqueda_externo.php?id_user='.$id_user.'&name_user='.$tipo_encuesta['name_user'].'_'.$tipo_encuesta['apellido'].'&id_tipo_encuesta='.$tipo_encuesta['id_tipo'];
                $data .= "<tr><td>"
                        . $tipo_encuesta['nombre']
                        . "</td><td>"
                        . $tipo_encuesta['descripcion']
                        . "</td><td>
                     <button class='btn btn-primary' title='Ver' onclick='window.open(\"$url\",\"_self\")'><i class='fa fa-eye'></i>
                </button></td></tr>";
            //}
        }
        $data .= "</tbody></table>";
        $datos = array(
            'result' => 'ok',
            'data' => $data
        );
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function traerMisEncuestasBusqueda($id_user,$id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd2 = new baseDatos();
    $bd2->Conectarse();
    //tengo que ver que permisos tiene ese usuario primero con ese tipo de encuesta, 
    //y después buscar segun ese permiso (o las propias o todas) las encuestas de ese tipo de encuesta
    $bd->select("SELECT * FROM mds_encuesta_usuario_tipo WHERE id_usuario = $id_user AND id_tipo = $id_tipo_encuesta AND baja_fecha IS NULL");
    if ($bd->numero_filas() > 0){ //ese usuario tiene permiso para esa encuesta
        //reviso los permisos de ese usuario sobre esa encuesta
        $permisos = $bd->registro();
        $respuesta_multiple = $permisos['respuesta_multiple']; //puede contestar más de una encuesta (1) o una sola (0)
        $respuesta_general = $permisos['respuesta_general']; //puede editar o continuar encuestas de otros (1) o sólo las propias (0)
        $reportes_generales = $permisos['reportes_generales']; //puede ver encuestas de otros (1) o las propias (0)
        //busco las encuestas segun el permiso de reportes_generales
        $andPropias = "";
        if (!$reportes_generales){ //busco todas las encuestas respondidas de ese tipo de encuesta
            $andPropias = " AND id_user = $id_user";
        }

        //busco las columnas que voy a mostrar para esa encuesta, son las preguntas de ese tipo de encuesta que tienen encabezado en 1
        $bd2->select("SELECT * FROM mds_encuesta_pregunta WHERE id_tipo_encuesta = $id_tipo_encuesta AND encabezado = 1");
        $columns = [];
        $text_columns = "";
        $indices_elem = array();
        $elem = array();
        $elem_botones = array();
        $elem_final = array();
        if ($bd2->numero_filas() > 0){
            $i = 1;
            $filas_columnas = $bd2->numero_filas();
            while ($columnas = $bd2->registro()){ 
                $columns[] = ["title"=>$columnas['pregunta']];
                $text_columns .= $columnas['id_pregunta'];
                if ($i < $filas_columnas)
                    $text_columns .= ",";
                $i++;
            }
            $columns[] = ["title"=> "acciones"];
        }
        array_unshift($columns, ["title"=>"Fecha"]);
        array_unshift($columns, ["title"=>"#"]);

        //busco las encuestas que puede visualizar ese usuario
        $i = 0;
        $bd1->select("SELECT *,date_format(date(fecha_creacion),'%d/%m/%Y') as fecha_creacion  FROM mds_encuesta LEFT JOIN mds_encuesta_resultado USING (id_encuesta) WHERE id_tipo_encuesta = $id_tipo_encuesta $andPropias AND id_pregunta IN ($text_columns) AND baja_fecha IS NULL ORDER BY id_pregunta,id_encuesta");
        syslog(LOG_NOTICE,"FILAS ".$bd1->numero_filas());
        if ($bd1->numero_filas() > 0){
            $idencuesta = '';
            while ($respuesta = $bd1->registro()){   
                //si no esta completa la puedo editar si es mia o si respuesta_general = 1 
                $elem[$respuesta['id_encuesta']]['fecha'] = $respuesta['fecha_creacion'];
                $elem[$respuesta['id_encuesta']]['id_encuesta'] = $respuesta['id_encuesta'];
                $url_edicion = "encuesta.php?id_user=".$id_user."&name_user=&id_encuesta=".$respuesta['id_encuesta']."&id_tipo_encuesta=".$id_tipo_encuesta;
                if (!$respuesta['completa'] && ($respuesta['id_user'] == $id_user || $respuesta_general)){
                    $elem[$respuesta['id_encuesta']]['botones'] = '<button class="btn btn-warning" title="Editar" onclick="editarEncuesta('.$id_user.','.$respuesta['id_encuesta'].','.$id_tipo_encuesta.');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> <button class="btn btn-primary" title="Clonar" onclick="clonarResultadoEncuesta('.$id_user.','.$respuesta['id_encuesta'].','.$id_tipo_encuesta.')"><i class="fa fa-clone" aria-hidden="true"></i></button> <button class="btn btn-success" title="Ver" onclick="verEncuesta('.$respuesta['id_encuesta'].','.$id_tipo_encuesta.',1);"><i class="fa fa-eye" aria-hidden="true"></i></button> <button class="btn btn-info" title="Imprimir" onclick="imprimirEncuesta('.$respuesta['id_encuesta'].','.$id_tipo_encuesta.')"><i class="fa fa-print" aria-hidden="true"></i></button> <button class="btn btn-danger" title="Eliminar" onclick="eliminarResultadoEncuesta('.$id_user.','.$respuesta['id_encuesta'].');"><i class="fa fa-close" aria-hidden="true"></i></button> ';                       
                }else{
                    $elem[$respuesta['id_encuesta']]['botones'] = '<button class="btn btn-primary" title="Clonar" onclick="clonarResultadoEncuesta('.$id_user.','.$respuesta['id_encuesta'].','.$id_tipo_encuesta.')"><i class="fa fa-clone" aria-hidden="true"></i></button> <button class="btn btn-success" title="Ver" onclick="verEncuesta('.$respuesta['id_encuesta'].','.$id_tipo_encuesta.',1);"><i class="fa fa-eye" aria-hidden="true"></i></button> <button class="btn btn-info" title="Imprimir" onclick="imprimirEncuesta('.$respuesta['id_encuesta'].','.$id_tipo_encuesta.')"><i class="fa fa-print" aria-hidden="true"></i></button>';
                }
                $elem[$respuesta['id_encuesta']]['valor'][$respuesta['id_pregunta']] = $respuesta['valor'];                   
            }
        }
        
        foreach( $elem as $value) { 
            $elem_final[] = array_values($value['valor']);
            array_unshift($elem_final[sizeof($elem_final)-1],$value['fecha']);
            array_unshift($elem_final[sizeof($elem_final)-1],$value['id_encuesta']);
            array_push($elem_final[sizeof($elem_final)-1],$value['botones']);
        }

        /*foreach( $elem_final as $key => $value) { 
            array_push($elem_final[$key],'<button class="btn btn-warning" title="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> <button class="btn btn-primary" title="Clonar"><i class="fa fa-clone" aria-hidden="true"></i></button> <button class="btn btn-success" title="Ver"><i class="fa fa-eye" aria-hidden="true"></i></button> <button class="btn btn-info" title="Imprimir"><i class="fa fa-print" aria-hidden="true"></i></button>');
        }*/
    }
        $data = array("draw"=> 1,
  "recordsTotal"=> 2,
  "recordsFiltered"=> 2,"data"=>$elem_final,"columns"=> $columns);
        
            $datos = array(
//}
            'result' => 'ok',
            'data' => json_encode($data),
            'columns' => json_encode($columns)
        );
   /* } else {
        $datos = array(
            'result' => 'no'
        );
    }*/
    header('Content-type: application/json');
    echo json_encode($data);
}

function traerMisEncuestasBusquedaExterna($id_user,$id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd2 = new baseDatos();
    $bd2->Conectarse();
    //tengo que ver que permisos tiene ese usuario primero con ese tipo de encuesta, 
    //y después buscar segun ese permiso (o las propias o todas) las encuestas de ese tipo de encuesta
    $bd->select("SELECT * FROM mds_encuesta_usuario_tipo WHERE id_usuario = $id_user AND id_tipo = $id_tipo_encuesta AND baja_fecha IS NULL");
    if ($bd->numero_filas() > 0){ //ese usuario tiene permiso para esa encuesta
        //reviso los permisos de ese usuario sobre esa encuesta
        $permisos = $bd->registro();
        $respuesta_multiple = $permisos['respuesta_multiple']; //puede contestar más de una encuesta (1) o una sola (0)
        $respuesta_general = $permisos['respuesta_general']; //puede editar o continuar encuestas de otros (1) o sólo las propias (0)
        $reportes_generales = $permisos['reportes_generales']; //puede ver encuestas de otros (1) o las propias (0)
        //busco las encuestas segun el permiso de reportes_generales
        $andPropias = "";
        if (!$reportes_generales){ //busco todas las encuestas respondidas de ese tipo de encuesta
            $andPropias = " AND id_user = $id_user";
        }

        //busco las columnas que voy a mostrar para esa encuesta, son las preguntas de ese tipo de encuesta que tienen encabezado en 1
        $bd2->select("SELECT * FROM mds_encuesta_pregunta WHERE id_tipo_encuesta = $id_tipo_encuesta AND encabezado = 1");
        $columns = [];
        $text_columns = "";
        $indices_elem = array();
        $elem = array();
        $elem_botones = array();
        $elem_final = array();
        if ($bd2->numero_filas() > 0){
            $i = 1;
            $filas_columnas = $bd2->numero_filas();
            while ($columnas = $bd2->registro()){ 
                $columns[] = ["title"=>$columnas['pregunta']];
                $text_columns .= $columnas['id_pregunta'];
                if ($i < $filas_columnas)
                    $text_columns .= ",";
                $i++;
            }
            $columns[] = ["title"=>"acciones"];
        }
        array_unshift($columns, ["title"=>"Fecha"]);
        array_unshift($columns, ["title"=>"#"]);

        //busco las encuestas que puede visualizar ese usuario
        $i = 0;
        $bd1->select("SELECT *,date_format(date(fecha_creacion),'%d/%m/%Y') as fecha_creacion  FROM mds_encuesta LEFT JOIN mds_encuesta_resultado USING (id_encuesta) WHERE id_tipo_encuesta = $id_tipo_encuesta $andPropias AND id_pregunta IN ($text_columns) AND baja_fecha IS NULL ORDER BY id_pregunta,id_encuesta");
        syslog(LOG_NOTICE,"FILAS ".$bd1->numero_filas());
        if ($bd1->numero_filas() > 0){
            $idencuesta = '';
            while ($respuesta = $bd1->registro()){   
                //si no esta completa la puedo editar si es mia o si respuesta_general = 1 
                $elem[$respuesta['id_encuesta']]['fecha'] = $respuesta['fecha_creacion'];
                $elem[$respuesta['id_encuesta']]['id_encuesta'] = $respuesta['id_encuesta'];
                $url_edicion = "encuesta.php?id_user=".$id_user."&name_user=&id_encuesta=".$respuesta['id_encuesta']."&id_tipo_encuesta=".$id_tipo_encuesta;
                if (!$respuesta['completa'] && ($respuesta['id_user'] == $id_user || $respuesta_general)){
                    $elem[$respuesta['id_encuesta']]['botones'] = '<button class="btn btn-success" title="Ver" onclick="verEncuesta('.$respuesta['id_encuesta'].','.$id_tipo_encuesta.',1);"><i class="fa fa-eye" aria-hidden="true"></i></button> <button class="btn btn-info" title="Imprimir" onclick="imprimirEncuesta('.$respuesta['id_encuesta'].','.$id_tipo_encuesta.')"><i class="fa fa-print" aria-hidden="true"></i></button> ';                       
                }else{
                    $elem[$respuesta['id_encuesta']]['botones'] = '<button class="btn btn-success" title="Ver" onclick="verEncuesta('.$respuesta['id_encuesta'].','.$id_tipo_encuesta.',1);"><i class="fa fa-eye" aria-hidden="true"></i></button> <button class="btn btn-info" title="Imprimir" onclick="imprimirEncuesta('.$respuesta['id_encuesta'].','.$id_tipo_encuesta.')"><i class="fa fa-print" aria-hidden="true"></i></button>';
                }
                $elem[$respuesta['id_encuesta']]['valor'][$respuesta['id_pregunta']] = $respuesta['valor'];                   
            }
        }
        
        foreach( $elem as $value) { 
            $elem_final[] = array_values($value['valor']);
            array_unshift($elem_final[sizeof($elem_final)-1],$value['fecha']);
            array_unshift($elem_final[sizeof($elem_final)-1],$value['id_encuesta']);
            array_push($elem_final[sizeof($elem_final)-1],$value['botones']);
        }

        /*foreach( $elem_final as $key => $value) { 
            array_push($elem_final[$key],'<button class="btn btn-warning" title="Editar"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> <button class="btn btn-primary" title="Clonar"><i class="fa fa-clone" aria-hidden="true"></i></button> <button class="btn btn-success" title="Ver"><i class="fa fa-eye" aria-hidden="true"></i></button> <button class="btn btn-info" title="Imprimir"><i class="fa fa-print" aria-hidden="true"></i></button>');
        }*/
    }
        $data = array("draw"=> 1,
  "recordsTotal"=> 2,
  "recordsFiltered"=> 2,"data"=>$elem_final,"columns"=> $columns);
        
            $datos = array(
//}
            'result' => 'ok',
            'data' => json_encode($data),
            'columns' => json_encode($columns)
        );
   /* } else {
        $datos = array(
            'result' => 'no'
        );
    }*/
    header('Content-type: application/json');
    echo json_encode($data);
}

function traerTipoEncuesta() {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd->select("SELECT * FROM mds_encuesta_tipo WHERE baja_fecha IS NULL");
    if ($bd->numero_filas() > 0) { //lo encontró
        $data = "<table class='table'>"
                . "<thead>"
                . "<tr>"
                . "<th>Tipo Encuesta</th>"
                . "<th>Descripcion</th>"
                . "<th>Acciones</th>"
                . "</tr>"
                . "</thead><tbody>";
        while ($tipo_encuesta = $bd->registro()) {
            $id_user = ($tipo_encuesta['id_user'] != null) ? $tipo_encuesta['id_user'] : 0;
            $data .= "<tr><td>"
                    . $tipo_encuesta['nombre']
                    . "</td><td>"
                    . $tipo_encuesta['descripcion']
                    . "</td><td>
            <button class='btn btn-success' title='Asignar Tipo Encuesta' onclick='asignarTipoEncuesta("
                    . $tipo_encuesta['id_tipo'] . ");'><i class='fa fa-users'></i></button></td></tr>"
                    . "<tr class='info'><td colspan='4' id='asignaciones_" . $tipo_encuesta['id_tipo'] . "'></td></tr>";
        }
        $data .= "</tbody></table>";
        $datos = array(
            'result' => 'ok',
            'data' => $data
        );
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function eliminarResultadoEncuesta($id_user,$id_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();

    if ($bd->select("UPDATE mds_encuesta SET baja_fecha = '" . date('Y-m-d H:i:s') . "',user_baja=$id_user WHERE id_encuesta = $id_encuesta")) {
        $datos = array(
            'result' => 'ok'
        );
    } else {
        $datos = array(
            'result' => 'no',
            'message' => "UPDATE mds_encuesta SET baja_fecha = '" . date('Y-m-d H:i:s') . "',user_baja=$id_user WHERE id_tipo = $id_encuesta"
        );
    }

    header('Content-type: application/json');
    echo json_encode($datos);
}

function eliminarEncuesta($id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();

    if ($bd->select("UPDATE mds_encuesta_tipo SET baja_fecha = '" . date('Y-m-d H:i:s') . "' WHERE id_tipo = $id_tipo_encuesta")) {
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

function eliminarTipoEncuesta($id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();

    if ($bd->select("UPDATE mds_encuesta_tipo SET baja_fecha = '" . date('Y-m-d H:i:s') . "' WHERE id_tipo = $id_tipo_encuesta")) {
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

function guardarTipoEncuesta($id, $tipo_encuesta, $descripcion, $texto_inicial,$texto_final, $id_user) {
    //error_log("HEADER ".$header);
    $bd = new baseDatos();
    $bd->Conectarse();
    if ($id == 0) { //tengo que insertar
        $query = "INSERT INTO mds_encuesta_tipo (nombre,descripcion,texto_inicial,texto_final,activo,id_user) VALUES ('$tipo_encuesta','$descripcion','$texto_inicial','$texto_final',1,$id_user)";
    } else { //tengo que actualizar
        $query = "UPDATE mds_encuesta_tipo SET nombre='$tipo_encuesta',descripcion='$descripcion',texto_inicial='$texto_inicial',texto_final='$texto_final',id_user=$id_user WHERE id_tipo = $id";
    }
    error_log($query);
    if ($bd->select($query)) {
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

function guardarEncuesta($id_user, $id_tipo_encuesta, $fecha_creacion) {
    $bd = new baseDatos();
    $bd->Conectarse();
    //tengo que insertar
    $query = "INSERT INTO mds_encuesta (id_user,id_tipo_encuesta,fecha_creacion) VALUES ('$id_user','$id_tipo_encuesta','$fecha_creacion')";

    if ($bd->select($query)) {
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

function guardarFamiliasolidaria($nombre_apellido,$dni,$fecha_nacimiento,$edad,$telefono,$mail,$localidad) {
    
    $id_user = 7801 ;
    $id_tipo_encuesta = 6;
    $fecha_creacion = date('Y-m-d H:i:s');
    $fecha_inscripcion = date('Y-m-d');

    $bd = new baseDatos();
    $bd->Conectarse();
    //se crea un registro en la tabla mds encuesta
    $query = "INSERT INTO mds_encuesta (id_user,id_tipo_encuesta,fecha_creacion) VALUES ('$id_user','$id_tipo_encuesta','$fecha_creacion')";

    //obtenemos el id de la encuesta que se creo 
    if ($bd->select($query)){ //se creo correctamente
        $bd->select("SELECT LAST_INSERT_ID()");
        $id = $bd->registro();
        $id_encuesta = $id['LAST_INSERT_ID()'];
    
    $query = "INSERT INTO mds_encuesta_resultado (id_encuesta,id_seccion,id_pregunta,valor) 
            VALUES ($id_encuesta,60,499,'$nombre_apellido'),
                   ($id_encuesta,60,500,'$dni'),
                   ($id_encuesta,60,501,'$fecha_nacimiento'),
                   ($id_encuesta,60,502,'$edad'),
                   ($id_encuesta,60,503,'$telefono'),
                   ($id_encuesta,60,504,'$mail'),
                   ($id_encuesta,60,506,'$localidad'),
                   ($id_encuesta,60,505,'$fecha_inscripcion')
    ";
    }


    if ($bd->select($query)) {
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
function clonarResultadoEncuesta($id_user,$id_encuesta,$id_tipo_encuesta){
    //tengo que crear una encuesta nueva y copiar todos los resultados de la encuesta anterior y devolver el id_encuesta nuevo
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $fecha_creacion = date('Y-m-d H:i:s');
    $bd->abrirtransaccion();
    $query = "INSERT INTO mds_encuesta (id_user,id_tipo_encuesta,fecha_creacion) VALUES ('$id_user','$id_tipo_encuesta','$fecha_creacion')";
    if ($bd->select($query)){ //se creo correctamente
        $bd->select("SELECT LAST_INSERT_ID()");
        $id = $bd->registro();
        $id_encuesta_new = $id['LAST_INSERT_ID()'];
        //ahora tengo que copiar todas las respuestas de la encuesta anterior
        $bd1->abrirtransaccion();
        if ($bd1->select("INSERT INTO mds_encuesta_resultado (id_encuesta,id_seccion,id_pregunta,id_respuesta,valor) 
        SELECT $id_encuesta_new,id_seccion,id_pregunta,id_respuesta,valor FROM mds_encuesta_resultado WHERE id_encuesta = $id_encuesta
         ")){
            $datos = array(
                'result' => 'ok',
                'id_encuesta' => $id_encuesta_new,
                'message' => 'ok'
             );
         }else{
            $datos = array(
               'result' => 'no',
               'id_encuesta' => $id_encuesta_new,
               'message' => "INSERT INTO mds_encuesta_resultado (id_encuesta,id_seccion,id_pregunta,id_respuesta,valor) 
                SELECT $id_encuesta_new,id_seccion,id_pregunta,id_respuesta,valor FROM mds_encuesta_resultado WHERE id_encuesta = $id_encuesta"
            );
            $bd->deshacertransaccion();
            $bd1->deshacertransaccion();        
         }

    }else{
        $bd->deshacertransaccion();
    }
    $bd->cerrartransaccion();
    $bd1->cerrartransaccion();
    header('Content-type: application/json');
    echo json_encode($datos);
}

function clonarEncuesta($id_tipo_encuesta, $id_tipo_encuesta_new) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd2 = new baseDatos();
    $bd2->Conectarse();
    $bd3 = new baseDatos();
    $bd3->Conectarse();
    $bd4 = new baseDatos();
    $bd4->Conectarse();
    $bd5 = new baseDatos();
    $bd5->Conectarse();
    $datos = array(
        'result' => 'ok',
    );
    //primero recorro todas las secciones y por cada seccion que copio tengo que copiar sus preguntas con sus respuestas
    $bd->select("SELECT * FROM mds_encuesta_seccion WHERE id_tipo_encuesta = $id_tipo_encuesta AND baja_fecha IS NULL");
    while ($seccion = $bd->registro()) {
        $id_seccion = $seccion['id_seccion'];
        //agrego la nueva seccion para la nueva empresa
        if ($bd1->select("INSERT INTO mds_encuesta_seccion (seccion,id_tipo_encuesta,explicacion,orden) VALUES ('" . $seccion['seccion'] . "',$id_tipo_encuesta_new,'" . $seccion['explicacion'] . "'," . $seccion['orden'] . ")")) {
            $id_seccion_new = $bd1->ultimo_id();
            //busco las preguntas para esta seccion y por cada pregunta que inserto nueva, busco sus respuestas y las guardo
            $bd2->select("SELECT * FROM mds_encuesta_pregunta WHERE id_seccion = " . $id_seccion . " AND baja_fecha IS NULL");
            while ($pregunta = $bd2->registro()) {
                $id_pregunta = $pregunta['id_pregunta'];
                if ($pregunta['orden'] == '') {
                    $orden = 0;
                } else
                    $orden = $pregunta['orden'];
                if ($pregunta['dependiente'] == '') {
                    $dependiente = 0;
                } else
                    $dependiente = $pregunta['dependiente'];
                if ($bd3->select("INSERT INTO mds_encuesta_pregunta (id_seccion,id_tipo_encuesta,pregunta,name,requerida,dependiente,orden) VALUES ($id_seccion_new,$id_tipo_encuesta_new,'" . $pregunta['pregunta'] . "','" . $pregunta['name'] . "'," . $pregunta['requerida'] . "," . $dependiente . "," . $orden . ")")) {
                    $id_pregunta_new = $bd3->ultimo_id();
                    //para cada pregunta busco las respuestas y las inserto 
                    $bd4->select("SELECT * FROM mds_encuesta_respuesta WHERE id_pregunta = $id_pregunta AND baja_fecha IS NULL");
                    while ($respuesta = $bd4->registro()) {
                        $id_respuesta = $respuesta['id_respuesta'];
                        if ($respuesta['orden'] == '') {
                            $ordenR = 0;
                        } else
                            $ordenR = $respuesta['orden'];
                        if ($bd5->select("INSERT INTO mds_encuesta_respuesta (id_pregunta,respuesta,tipo,name,value,otro_campo,texto_previo_desde,radio_desde,radio_hasta,texto_posterior_hasta,imagen,orden) VALUES "
                                        . "($id_pregunta_new,'" . $respuesta['respuesta'] . "','" . $respuesta['tipo'] . "','" . $respuesta['name'] . "','" . $respuesta['value'] . "'," . $respuesta['otro_campo'] . ",'" . $respuesta['texto_previo_desde'] . "'," . $respuesta['radio_desde'] . "," . $respuesta['radio_hasta'] . ",'" . $respuesta['texto_posterior_hasta'] . "','" . $respuesta['imagen'] . "'," . $ordenR . ")")) {
                            $datos = array(
                                'result' => 'ok',
                            );
                        } else {
                            error_log("INSERT INTO mds_encuesta_respuesta (id_pregunta,respuesta,tipo,name,value,otro_campo,texto_previo_desde,radio_desde,radio_hasta,texto_posterior_hasta,imagen,orden) VALUES "
                                    . "($id_pregunta_new,'" . $respuesta['respuesta'] . "','" . $respuesta['tipo'] . "','" . $respuesta['name'] . "','" . $respuesta['value'] . "'," . $respuesta['otro_campo'] . ",'" . $respuesta['texto_previo_desde'] . "'," . $respuesta['radio_desde'] . "," . $respuesta['radio_hasta'] . ",'" . $respuesta['texto_posterior_hasta'] . "','" . $respuesta['imagen'] . "'," . $ordenR . ")");
                            $datos = array(
                                'result' => 'no'
                            );
                        }
                    }
                } else {
                    error_log("INSERT INTO mds_encuesta_pregunta (id_seccion,id_tipo_encuesta,pregunta,name,requerida,dependiente,orden) VALUES ($id_seccion_new,$id_tipo_encuesta_new,'" . $pregunta['pregunta'] . "','" . $pregunta['name'] . "'," . $pregunta['requerida'] . "," . $dependiente . "," . $orden . ")");
                    $datos = array(
                        'result' => 'no'
                    );
                }
            }
        } else {
            error_log("INSERT INTO mds_encuesta_seccion (seccion,id_tipo_encuesta,explicacion,orden) VALUES ('" . $seccion['seccion'] . "',$id_tipo_encuesta_new,'" . $seccion['explicacion'] . "'," . $seccion['orden'] . ")");
            $datos = array(
                'result' => 'no'
            );
        }
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function traerAsignaciones($id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd1->select("SELECT * FROM mds_encuesta_tipo WHERE id_tipo = $id_tipo_encuesta");
    if ($bd1->numero_filas() > 0) {
        $enc = $bd1->registro();
        $texto = $enc['nombre'];
    } else {
        $texto = "";
    }
    $bd->select("SELECT * FROM mds_encuesta_usuario_tipo JOIN mds_seg_usuario ON (id_usuario = idusuario) WHERE id_tipo = $id_tipo_encuesta AND baja_fecha IS NULL ORDER BY apellido ASC");

    $data = "<table class='table table-striped'>"
            . "<thead>"
            . "<tr><th colspan='3'><h4>Usuarios de $texto</h4></th><th><button class='btn btn-info' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Nueva Asignación' data-target='#encuesta_modal' href='modalAsignacion.php?id_tipo_encuesta=$id_tipo_encuesta'><i class='fa fa-plus'></i> Asignar Usuarios</button><button class='btn btn-default' onclick='ocultarAsignaciones($id_tipo_encuesta);'><i class='fa fa-eye-slash'></i></button></th></tr>";
    if ($bd->numero_filas() > 0) { //lo encontró            
        $data .= "<tr>"
                . "<th>Usuario</th>"
                . "<th>Dni</th>"
                . "<th>Mail</th>"
                . "<th>Puede responder varias encuestas?</th>"
                . "<th>Puede continuar encuestas de otros?</th>"
                . "<th>Puede ver reportes generales?</th>"
                . "<th>Acciones</th>"
                . "</tr>";
        while ($asignacion = $bd->registro()) {
            $respuesta_multiple = ($asignacion['respuesta_multiple'] == 0) ? 'No' : 'Sí';
            $respuesta_general = ($asignacion['respuesta_general'] == 0) ? 'No' : 'Sí';
            $reportes_generales = ($asignacion['reportes_generales'] == 0) ? 'No' : 'Sí';
            $data .= "</thead><tbody><tr class='active'><td>" . strtoupper($asignacion['apellido'] . " " . $asignacion['nombre']) . "</td><td>" . $asignacion['dni'] . "</td><td>"
                    . $asignacion['mail'] . "</td><td>" . $respuesta_multiple . "</td><td>" . $respuesta_general . "</td><td>" . $reportes_generales . "</td><td><button class='btn btn-warning' title='Editar Asignación' data-id='" . $asignacion['id_usuario_tipo']
                    . "' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Editar Asignación' data-target='#encuesta_modal' href='modalAsignacion.php?id="
                    . $asignacion['id_usuario_tipo'] . "&id_tipo_encuesta=$id_tipo_encuesta'><i class='fa fa-edit'></i></button>&nbsp;<button class='btn btn-danger' title='Eliminar Asignación' onclick='if (confirm(\"¿Desea eliminar la asignación?\")) { borrarAsignacion($id_tipo_encuesta,"
                    . $asignacion['id_usuario_tipo'] . "); }'><i class='fa fa-remove'></i></button></td></tr>";
        }
    } else {
        $data .= "</thead>";
    }
    $data .= "</tbody></table>";
    $datos = array(
        'result' => 'ok',
        'data' => $data
    );
    header('Content-type: application/json');
    echo json_encode($datos);
}

//ASIGNAR USUARIOS A ENCUESTAS
function guardarAsignacion($id, $id_tipo_encuesta, $respuesta_multiple, $respuesta_general, $reportes_generales, $users) {
    $bd = new baseDatos();
    $bd1 = new baseDatos();
    $bd->Conectarse();
    $bd1->Conectarse();
    if ($id == 0) { //tengo que insertar
        for ($i = 0; $i <= sizeof($users); $i++) {
            $usuario = $users[$i];
            $bd1->select("SELECT * FROM mds_encuesta_usuario_tipo WHERE id_usuario = $usuario AND id_tipo = $id_tipo_encuesta");
            if ($bd1->numero_filas() > 0) { //actualizo
                $user_tipo = $bd1->registro();
                $bd->select("UPDATE mds_encuesta_usuario_tipo SET respuesta_multiple = $respuesta_multiple,respuesta_general=$respuesta_general,reportes_generales=$reportes_generales,baja_fecha=NULL WHERE id_usuario_tipo = ".$user_tipo['id_usuario_tipo']);
            } else { //inserto
                $bd->select("INSERT INTO mds_encuesta_usuario_tipo (id_tipo,id_usuario,respuesta_multiple,respuesta_general,reportes_generales) VALUES ($id_tipo_encuesta,$usuario,$respuesta_multiple,$respuesta_general,$reportes_generales)");
            }
        }
        $datos = array(
            'result' => 'ok'
        );
    } else { //tengo que actualizar
        if ($bd->select("UPDATE mds_encuesta_usuario_tipo SET respuesta_multiple = $respuesta_multiple,respuesta_general=$respuesta_general,reportes_generales=$reportes_generales WHERE id_usuario_tipo = $id")) {
            $datos = array(
                'result' => 'ok'
            );
        } else {
            $datos = array(
                'result' => 'no'
            );
        }
    }

    header('Content-type: application/json');
    echo json_encode($datos);
}

function borrarAsignacion($id) {
    $bd = new baseDatos();
    $bd->Conectarse();

    if ($bd->select("UPDATE mds_encuesta_usuario_tipo SET baja_fecha = '" . date('Y-m-d H:i:s') . "' WHERE id_usuario_tipo = $id")) {
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

function traerSecciones($id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd1->select("SELECT * FROM mds_encuesta_tipo WHERE id_tipo = $id_tipo_encuesta");
    if ($bd1->numero_filas() > 0) {
        $enc = $bd1->registro();
        $texto = $enc['nombre'];
    } else {
        $texto = "";
    }
    $bd->select("select * from mds_encuesta_seccion WHERE id_tipo_encuesta = $id_tipo_encuesta AND baja_fecha IS NULL ORDER BY orden ASC");
    $data = "<table class='table table-striped'>"
            . "<thead>"
            . "<tr><th colspan='3'><h4>Secciones de $texto</h4></th><th><button class='btn btn-info' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Nueva Seccion' data-target='#encuesta_modal' href='modalSeccion.php?id_seccion=0&id_tipo_encuesta=$id_tipo_encuesta'><i class='fa fa-plus'></i> Crear Nueva Seccion</button><button class='btn btn-default' onclick='ocultarSecciones($id_tipo_encuesta);'><i class='fa fa-eye-slash'></i></button></th></tr>";
    if ($bd->numero_filas() > 0) { //lo encontró
        while ($seccion = $bd->registro()) {
            $data .= "<tr>"
                    . "<th>Seccion</th>"
                    . "<th>Explicación</th>"
                    . "<th>Orden</th>"
                    . "<th>Acciones</th>"
                    . "</tr>"
                    . "</thead><tbody><tr class='active'><td>" . $seccion['seccion'] . "</td><td>" . $seccion['explicacion'] . "</td><td>#"
                    . $seccion['orden'] . "</td><td><button class='btn btn-warning' title='Editar Seccion' data-id='" . $seccion['id_seccion']
                    . "' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Editar Seccion' data-target='#encuesta_modal' href='modalSeccion.php?id_seccion="
                    . $seccion['id_seccion'] . "&id_tipo_encuesta=$id_tipo_encuesta'><i class='fa fa-edit'></i></button>&nbsp;<button class='btn btn-info' title='Administrar Preguntas' onclick='administrarPreguntas("
                    . $seccion['id_seccion'] . ",$id_tipo_encuesta);'><i class='fa fa-list-ol'></i></button>&nbsp;<button class='btn btn-danger' title='Eliminar Seccion' onclick='if (confirm(\"¿Desea eliminar la sección?\")) { borrarSeccion("
                    . $seccion['id_seccion'] . "," . $id_tipo_encuesta . "); }'><i class='fa fa-remove'></i></button></td></tr>"
                    . "<tr class='info'><td colspan='4' id='preguntas_" . $seccion['id_seccion'] . "'></td></tr>";
        }
    } else {
        $data .= "</thead>";
    }
    $data .= "</tbody></table>";
    $datos = array(
        'result' => 'ok',
        'data' => $data
    );
    header('Content-type: application/json');
    echo json_encode($datos);
}

function guardarSeccion($id, $seccion, $explicacion, $orden, $id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    if ($id == 0) { //tengo que insertar
        $query = "INSERT INTO mds_encuesta_seccion (seccion,explicacion,orden,id_tipo_encuesta) VALUES ('$seccion','$explicacion','$orden','$id_tipo_encuesta')";
    } else { //tengo que actualizar
        $query = "UPDATE mds_encuesta_seccion SET seccion='$seccion',explicacion='$explicacion',orden='$orden' WHERE id_seccion = $id";
    }
    if ($bd->select($query)) {
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

function borrarSeccion($id) {
    $bd = new baseDatos();
    $bd->Conectarse();

    if ($bd->select("UPDATE mds_encuesta_seccion SET baja_fecha = '" . date('Y-m-d H:i:s') . "' WHERE id_seccion = $id")) {
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

//PREGUNTAS
function traerPreguntas($id, $id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd->select("SELECT * FROM mds_encuesta_pregunta WHERE id_tipo_encuesta = $id_tipo_encuesta AND id_seccion = $id AND baja_fecha IS NULL ORDER BY  ISNULL(orden), orden ASC");
    $data = "<table class='table table-bordered'>"
            . "<thead>"
            . "<tr><th colspan='3'><h4>Preguntas</h4></th><th>
                    <button class='btn btn-info' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Nueva Seccion' data-target='#encuesta_modal' 
                    href='modalPregunta.php?id_seccion=$id&id_tipo_encuesta=$id_tipo_encuesta&id=0'><i class='fa fa-plus'></i> Crear Nueva Pregunta
                    </button><button class='btn btn-default' onclick='ocultarPreguntas($id);'><i class='fa fa-eye-slash'></i></button></th></tr>"
            . "<tr>"
            . "<th>Pregunta</th>"
            . "<th>Requerida</th>"
            . "<th>Orden</th>"
            . "<th>Acciones</th>"
            . "</tr>"
            . "</thead><tbody>";
    if ($bd->numero_filas() > 0) { //lo encontró
        while ($seccion = $bd->registro()) {
            $data .= "<tr><td>" . $seccion['pregunta'] . "</td><td>";
            if ($seccion['requerida'] == 1)
                $data .= "<b>Sí</b>";
            else
                $data .= "<b>No</b>";
            $data .= "</td><td>#" . $seccion['orden'] . "</td><td><button class='btn btn-warning' title='Editar Pregunta' data-id='" . $seccion['id_pregunta'] . "' data-dismiss='modal' data-keyboard='true' data-toggle='modal' 
                data-title='Editar Pregunta' data-target='#encuesta_modal' href='modalPregunta.php?id_seccion=" . $id . "&id_tipo_encuesta=$id_tipo_encuesta&id=" . $seccion['id_pregunta'] . "'><i class='fa fa-edit'></i></button>&nbsp;
                <button class='btn btn-info' title='Administrar Respuestas' onclick='administrarRespuestas(" . $seccion['id_pregunta'] . ",$id_tipo_encuesta);'><i class='fa fa-list'></i></button>&nbsp;<button class='btn btn-danger' title='Eliminar Pregunta' onclick='if (confirm(\"¿Desea eliminar la pregunta?\")) { borrarPregunta(" . $seccion['id_pregunta'] . ",$id," . $id_tipo_encuesta . "); }'><i class='fa fa-remove'></i></button></td></tr>"
                    . "<tr class='danger'><td colspan='4' id='respuestas_" . $seccion['id_pregunta'] . "'></td></tr>";
        }
    }
    $data .= "</tbody></table>";
    $datos = array(
        'result' => 'ok',
        'data' => $data
    );
    header('Content-type: application/json');
    echo json_encode($datos);
}

function guardarPregunta($id, $pregunta, $requerida, $encabezado, $orden, $busqueda, $id_seccion, $id_tipo_encuesta) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $orden = (!$orden) ? 0 : $orden;
    if ($id == 0) { //tengo que insertar
        $query = "INSERT INTO mds_encuesta_pregunta (pregunta,requerida,encabezado, orden,busqueda,id_seccion,id_tipo_encuesta) VALUES ('$pregunta','$requerida','$encabezado',$orden, $busqueda,'$id_seccion','$id_tipo_encuesta')";
    } else { //tengo que actualizar
        $query = "UPDATE mds_encuesta_pregunta SET pregunta='$pregunta',requerida=$requerida,encabezado=$encabezado,orden=$orden,busqueda=$busqueda WHERE id_pregunta = $id";
    }
    if ($bd->select($query)) {
        $datos = array(
            'result' => 'ok'
        );
    } else {
        $datos = array(
            'result' => $query
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function borrarPregunta($id) {
    $bd = new baseDatos();
    $bd->Conectarse();

    if ($bd->select("UPDATE mds_encuesta_pregunta SET baja_fecha = '" . date('Y-m-d H:i:s') . "' WHERE id_pregunta = $id")) {
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

//PREGUNTAS
//RESPUESTAS
function traerRespuestas($id) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $bd->select("SELECT * FROM mds_encuesta_respuesta WHERE id_pregunta = $id AND baja_fecha IS NULL ORDER BY ISNULL(orden), orden ASC");

    $data = "<table class='table table-bordered col-xs-9'>"
            . "<thead>"
            . "<tr><th colspan='4' class='text-info'><h4>RESPUESTAS</h4></th><th><button class='btn btn-info' data-dismiss='modal' data-keyboard='true' data-toggle='modal' 
                data-title='Nueva Respuesta' data-target='#encuesta_modal' 
                href='modalRespuestaNew.php?id_pregunta=" . $id . "'><i class='fa fa-plus'></i> Crear Nueva Respuesta</button><button class='btn btn-default' onclick='ocultarRespuestas($id);'><i class='fa fa-eye-slash'></i></button></th></tr>"
            . "<tr>"
            . "<th>Valor</th>"
            . "<th>Tipo de Respuesta</th>"
            . "<th>Nombre</th>"
            . "<th>Orden</th>"
            . "<th>Acciones</th>"
            . "</tr>"
            . "</thead><tbody>";
    while ($respuesta = $bd->registro()) {
        switch ($respuesta['tipo']) {
            case 'radio':
                $tipo = '<input type="radio" disabled> Selección Única';
                break;
            case 'radio_otro':
                $tipo = '<input type="radio" disabled>Otro <input type="text" disabled> Selección única, describe';
                break;
            case 'radio_varios':
                $tipo = $respuesta['texto_previo_desde'];
                for ($i = $respuesta['radio_desde']; $i <= $respuesta['radio_hasta']; $i++) {
                    $tipo .= ' <input type="radio" disabled>' . $i;
                }
                $tipo .= $respuesta['texto_posterior_hasta'] . ' -Escala lineal';
                break;
            case 'radio_varios_imagen':
                $tipo = '<img src="../images/' . $respuesta['imagen'] . '" width="40%">';
                for ($i = $respuesta['radio_desde']; $i <= $respuesta['radio_hasta']; $i++) {
                    $tipo .= ' <input type="radio" disabled>' . $i;
                }
                $tipo .= ' - Escala lineal c/imágen';
                break;
            case 'checkbox':
                $tipo = '<input type="checkbox" disabled>Selección múltiple';
                break;
            case 'textarea':
                $tipo = '<textarea disabled row="2" col="5"></textarea> Párrafo';
                break;
            case 'input':
                $tipo = '<input type="text" disabled> Respuesta Corta';
                break;
        }
        $data .= "<tr>";
        $data .= "<td>" . $respuesta['respuesta'] . "</td>"
                . "<td>" . $tipo . "</td>"
                . "<td>" . $respuesta['name'] . "</td>";
        $data .= "<td > #" . $respuesta['orden'] . "</td>";
        $data .= "<td><button class='btn btn-warning' title='Editar Respuesta' data-id='" . $respuesta['id_respuesta'] . "' data-dismiss='modal' data-keyboard='true' data-toggle='modal' data-title='Editar Respuesta' data-target='#encuesta_modal' href='modalRespuesta.php?id_pregunta=" . $respuesta['id_pregunta'] . "&id=" . $respuesta['id_respuesta'] . "'><i class='fa fa-edit'></i></button>&nbsp;<button class='btn btn-danger' title='Eliminar Respuesta' onclick='if (confirm(\"¿Desea eliminar la respuesta?\")) { borrarRespuesta(" . $respuesta['id_respuesta'] . "," . $id . "); }'><i class='fa fa-remove'></i></button></td></tr>"
                . "<tr><td colspan='4' id='respuesta_" . $respuesta['id_respuesta'] . "'></td></tr>";
        $datos = array(
            'result' => 'ok',
            'data' => $data
        );
    }
    $data .= "</tbody></table>";
    $datos = array(
        'result' => 'ok',
        'data' => $data
    );

    header('Content-type: application/json');
    echo json_encode($datos);
}

function guardarRespuesta($id, $id_pregunta, $tipo, $orden) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $query = "";
    $query1 = "";
    if ($orden == "")
        $orden = 0;
    switch ($tipo) {
        case 'radio':
            $query = "UPDATE mds_encuesta_respuesta SET respuesta = '" . $_REQUEST['respuesta'] . "',value='" . strtolower($_REQUEST['respuesta']) . "',orden=" . $orden . " WHERE id_respuesta =" . $id;
            if ($_REQUEST['radio_a_checkbox'] == 1)
                $query1 = "UPDATE mds_encuesta_respuesta SET tipo = 'checkbox' WHERE id_pregunta = $id_pregunta AND tipo = 'radio'";
            break;
        case 'radio_otro':
            $query = 'UPDATE mds_encuesta_respuesta SET respuesta = "' . $_REQUEST['respuesta'] . '",value="' . strtolower($_REQUEST['respuesta']) . '",orden=' . $orden . ' WHERE id_respuesta = ' . $id;
            break;
        case 'radio_varios':
            $query = 'UPDATE mds_encuesta_respuesta SET texto_previo_desde = "' . $_REQUEST['texto_previo_desde'] . '",radio_desde="' . $_REQUEST['radio_desde'] . '",radio_hasta="' . $_REQUEST['radio_hasta'] . '",texto_posterior_hasta="' . $_REQUEST['texto_posterior_hasta'] . '",orden=' . $orden . ' WHERE id_respuesta = ' . $id;
            break;
        case 'radio_varios_imagen':
            $query = 'UPDATE mds_encuesta_respuesta SET radio_desde="' . $_REQUEST['radio_desde'] . '",radio_hasta="' . $_REQUEST['radio_hasta'] . '",imagen="' . $_REQUEST['imagen'] . '",orden=' . $orden . ' WHERE id_respuesta = ' . $id;
            break;
        case 'checkbox':
            $query = 'UPDATE mds_encuesta_respuesta SET respuesta = "' . $_REQUEST['respuesta'] . '",value="' . strtolower($_REQUEST['respuesta']) . '",orden=' . $orden . ' WHERE id_respuesta = ' . $id;
            if ($_REQUEST['checkbox_a_radio'] == 1)
                $query1 = "UPDATE mds_encuesta_respuesta SET tipo = 'radio' WHERE id_pregunta = $id_pregunta AND tipo = 'checkbox'";
            break;
        case 'textarea':
            $query = 'UPDATE mds_encuesta_respuesta SET orden=' . $orden . ' WHERE id_respuesta = ' . $id;
            if ($_REQUEST['textarea_a_input'] == 1)
                $query1 = "UPDATE mds_encuesta_respuesta SET tipo = 'input' WHERE id_pregunta = $id_pregunta AND tipo = 'textarea'";
            break;
        case 'input':
            $query = 'UPDATE mds_encuesta_respuesta SET orden=' . $orden . ' WHERE id_respuesta = ' . $id;
            if ($_REQUEST['input_a_textarea'] == 1)
                $query1 = "UPDATE mds_encuesta_respuesta SET tipo = 'textarea' WHERE id_pregunta = $id_pregunta AND tipo = 'input'";
            break;
    }

    if ($bd->select($query)) {
        if ($query1 != "") {
            if ($bd1->select($query1)) {
                $datos = array(
                    'result' => 'ok'
                );
            } else {
                $datos = array(
                    'result' => 'no'
                );
            }
        } else {
            $datos = array(
                'result' => 'ok'
            );
            syslog(LOG_NOTICE, $query);
        }
    } else {
        $datos = array(
            'result' => 'no'
        );
    }
    header('Content-type: application/json');
    echo json_encode($datos);
}

function guardarRespuestaNew($id_pregunta, $tipo, $orden) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $query = "";
    if ($orden == "")
        $orden = 0;
    $bd->select("SELECT * FROM mds_encuesta_respuestas WHERE id_pregunta = $id_pregunta LIMIT 1");
    if ($bd->numero_filas() > 0) {
        $pregunta = $bd->registro();
        $name = $pregunta['name'];
    } else {
        $name = "pregunta_" . $id_pregunta;
    }
    if ($orden == "")
        $orden = 0;
    switch ($tipo) {
        case 'radio':
            $query = "INSERT INTO mds_encuesta_respuesta (id_pregunta,respuesta,tipo,name,value,orden) VALUES ($id_pregunta,'" . $_REQUEST['respuesta'] . "','$tipo','$name','" . strtolower($_REQUEST['respuesta']) . "',$orden)";
            break;
        case 'radio_otro':
            $query = "INSERT INTO mds_encuesta_respuesta (id_pregunta,respuesta,tipo,name,value,otro_campo,orden) VALUES ($id_pregunta,'" . $_REQUEST['respuesta'] . "','$tipo','$name','" . strtolower($_REQUEST['respuesta']) . "',1,$orden)";
            break;
        case 'radio_varios':
            $query = "INSERT INTO mds_encuesta_respuesta (id_pregunta,tipo,name,texto_previo_desde,radio_desde,radio_hasta,texto_posterior_hasta,orden) VALUES  ($id_pregunta,'$tipo','$name','" . $_REQUEST['texto_previo_desde'] . "','" . $_REQUEST['radio_desde'] . "','" . $_REQUEST['radio_hasta'] . "','" . $_REQUEST['texto_posterior_hasta'] . "',$orden)";
            break;
        case 'radio_varios_imagen':
            $query = "INSERT INTO mds_encuesta_respuesta (id_pregunta,tipo,name,radio_desde,radio_hasta,imagen,orden) VALUES ($id_pregunta,'$tipo','$name','" . $_REQUEST['radio_desde'] . "','" . $_REQUEST['radio_hasta'] . "','" . $_REQUEST['imagen'] . "',$orden)";
            break;
        case 'checkbox':
            $query = "INSERT INTO mds_encuesta_respuesta (id_pregunta,respuesta,tipo,name,value,orden) VALUES ($id_pregunta,'" . $_REQUEST['respuesta'] . "','$tipo','$name','" . strtolower($_REQUEST['respuesta']) . "',$orden)";
            break;
        case 'textarea':
            $query = "INSERT INTO mds_encuesta_respuesta (id_pregunta,tipo,name,orden) VALUES ($id_pregunta,'$tipo','$name',$orden)";
            break;
        case 'input':
            $query = "INSERT INTO mds_encuesta_respuesta (id_pregunta,tipo,name,orden) VALUES($id_pregunta,'$tipo','$name',$orden)";
            break;
    }
    syslog(LOG_NOTICE, $query);
    if ($bd->select($query)) {
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

function borrarRespuesta($id) {
    $bd = new baseDatos();
    $bd->Conectarse();

    if ($bd->select("UPDATE mds_encuesta_respuesta SET baja_fecha = '" . date('Y-m-d H:i:s') . "' WHERE id_respuesta = $id")) {
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

//RESPUESTAS
?>
