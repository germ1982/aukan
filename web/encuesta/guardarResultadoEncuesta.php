<?php

$dirBase = "";
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
require __DIR__ . '/../../vendor/autoload.php';
$bd = new baseDatos();
$bd->Conectarse();
$bd1 = new baseDatos();
$bd1->Conectarse();
$base = new baseDatos();
$base->Conectarse();


$id_encuesta = $_REQUEST['id_encuesta'];
$id_seccion = $_REQUEST['id_seccion'];
$id_user = $_REQUEST['id_user'];
$query = "";
$query1 = "";
$i = 0;
$preguntas = array();
foreach ($_POST as $key => $val) {
    if ($key == 'id_encuesta')
        $id_encuesta = $val;
    if ($key != 'id_encuesta' && $key != 'id_seccion' && $key != 'id_user' && $key != 'name_user' && $key != 'id_tipo_encuesta' && $key != 'ultima_seccion') { //son las respuestas
        $id_pregunta = $key;
        $id_preg = $id_pregunta;
        $id_pregunta = str_replace('otro_', '', $id_pregunta);
        $id_preg = str_replace('otro_', '', $id_pregunta);
        if (strpos($id_pregunta, 'check_') !== false) {
            $preg = explode("-", $id_pregunta);
            $id_pregunta = $preg[1];
        }
        $arrayPreguntasDniSennya = array(134, 370, 541, 630); // Preguntas de SENNYA's que guardan DNI
        $valTrim = str_replace('.', '', $val);
        $valTrim = str_replace(' ', '', $valTrim);
        if (in_array($id_pregunta, $arrayPreguntasDniSennya)) {
            $callCurl = curl_init();
            $payload = array(
                'documento' => $valTrim,
                'user_id' => $id_user
            );
            curl_setopt($callCurl, CURLOPT_URL, env('ENDPOINT_RUNNEU_NUEVA_SENNYA'));
            curl_setopt($callCurl, CURLOPT_POST, 1);
            curl_setopt($callCurl, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($callCurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($callCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($callCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $exec = curl_exec($callCurl);
        }

        $bd->select("SELECT * FROM mds_encuesta_resultado WHERE id_encuesta = $id_encuesta AND id_seccion = $id_seccion AND id_pregunta = $id_pregunta"); //reviso si ya cargue esa respuesta tengo que actualizar, sino cargo de nuevo
        error_log("SELECT * FROM mds_encuesta_resultado WHERE id_encuesta = $id_encuesta AND id_seccion = $id_seccion AND id_pregunta = $id_pregunta");
        if ($bd->numero_filas() > 0) { //actualizo
            $encuesta = $bd->registro();
            error_log("ES CHECKBOK ??? " . strpos($id_pregunta, 'check_'));
            if ($val != '') {
                if (strpos($id_preg, 'check_') !== false) { //si son checkbox, elimino todos los resultados la primera vez y luego inserto y desp se van a insertar los nuevos
                    if (!in_array($id_pregunta, $preguntas)) { //solo lo hago la primera vez que traigo ese checkbox

                        $bd1->select("DELETE FROM mds_encuesta_resultado WHERE id_encuesta = $id_encuesta AND id_pregunta = $id_pregunta");
                        error_log("DELETE FROM mds_encuesta_resultado WHERE id_encuesta = $id_encuesta AND id_pregunta = $id_pregunta");
                        $preguntas[] = $id_pregunta;
                    }
                    $query = "INSERT INTO mds_encuesta_resultado (id_encuesta,id_seccion,id_pregunta,valor) VALUES ($id_encuesta,$id_seccion,$id_pregunta,'$val')";
                } else {
                    $query = "UPDATE mds_encuesta_resultado SET valor = '$val' WHERE id_resultado = " . $encuesta['id_resultado'];
                }
            }
        } else { //inserto


            $query = "INSERT INTO mds_encuesta_resultado (id_encuesta,id_seccion,id_pregunta,valor) VALUES ($id_encuesta,$id_seccion,$id_pregunta,'$val')";
        }
        // error_log($query);
        if ($base->select($query)) {
            $datos = array(
                'result' => 'ok',
            );
        } else {
            if ($query == '') {
            } else {
                $datos = array(
                    'result' => 'no',
                );
                error_log("NO QUERY: " . $query);
            }
        }
    } else {
        if ($key == 'ultima_seccion') {
            $query1 = "UPDATE mds_encuesta SET completa = 1 WHERE id_encuesta = $id_encuesta";
            $base->select($query1);
        }
        $datos = array(
            'result' => 'ok',
        );
    }
}

/* if ($password == $password_ant){
  $datos = array(
  'result' => 'igual'
  );
  }else{
  if ($bd->select("UPDATE pacientes_temp SET password='$password',cambio_password=1 WHERE idpaciente = $idpaciente")){
  $datos = array(
  'result' => 'ok',
  'idpaciente' => $idpaciente
  );
  }else{
  $datos = array(
  'result' => 'no'
  );
  }
  } */
error_log($query1);
if ($datos['result'] == 'ok' && $query1 != "") { //tengo que cerrar la encuesta
    $base->select($query1);
}
echo json_encode($datos, JSON_FORCE_OBJECT);
