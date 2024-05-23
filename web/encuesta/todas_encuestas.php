<?php
$dirBase = '';
require_once $dirBase . "../xajax/xajax.inc.php";
$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->decodeUTF8InputOn();
require_once $dirBase . 'comun/conectarse.php';
require_once $dirBase . 'comun/libreria.php';
require_once $dirBase . "comun/clases/clase_encuesta.php";
require_once $dirBase . 'comun/clases/clase_profesionales.php';
require_once $dirBase . 'comun/clases/clase_obra_social.php';
require_once $dirBase . 'comun/clases/clase_obra_social.php';
require_once $dirBase . 'comun/clases/clase_patron_iterator.php';
require_once $dirBase . 'comun/clases/clase_listar.php';
//El objeto xajax tiene que procesar cualquier petici�n
$xajax->processRequests();
$id = $_REQUEST['id'];
$idprofesional = $_REQUEST['idprofesional'];
$idpaciente = $_REQUEST['idpaciente'];
$xajax->printJavascript($dirBase . "xajax/");
$bd = new baseDatos();
$bd->Conectarse();
$bd1 = new baseDatos();
$bd1->Conectarse();
$bd2 = new baseDatos();
$bd2->Conectarse();
$bd->select("SELECT * FROM encuesta WHERE idpaciente = $idpaciente ORDER BY fecha_creacion DESC");
if ($bd->numero_filas() > 0) {
    ?>
    <div class="container-fluid">
        <!-- Header -->
        <div id="encuesta">
            <?php
            while ($encuesta = $bd->registro()) {
                $id_encuesta = $encuesta['id'];
                $bd1->select("select * from encuesta_resultado as r
                JOIN encuesta_preguntas as p ON (r.id_pregunta = p.id) 
                JOIN encuesta_secciones as s ON (r.id_seccion = s.id)
                WHERE r.id_encuesta = $id_encuesta
                order by r.id_seccion,s.orden ASC");
                ?>                
                <div class="panel panel-minimizable panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-10">
                                <div class="row">
                                    <div class="col-xs-4"><strong>Encuesta</strong></div>
                                    <div class="col-xs-4"><strong>Fecha Creación:</strong> 
                                        <?php echo date('d/m/Y', strtotime($encuesta['fecha_creacion'])); ?>
                                    </div> 
                                    <div class="col-xs-4"><strong>Fecha de vinculación:</strong> 
                                        <?php echo date('d/m/Y', strtotime($encuesta['fecha_vinculacion'])); ?></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-10">
                            <?php
                            $id_seccion = 1;
                            $texto_seccion = "Datos Generales";
                            $pregunta = "";
                            ?>
                            <div class="row">
                                <div class="col-xs-12"><h4><strong><?php echo $texto_seccion; ?></strong></h4></div>
                            </div>
                            <?php
                            while ($resultados = $bd1->registro()) {
                                if ($resultados['id_seccion'] != $id_seccion) {
                                    $id_seccion = $resultados['id_seccion'];
                                    $texto_seccion = $resultados['seccion'];
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12"><h4><strong><?php echo $texto_seccion; ?></strong></h4></div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="row">
                                    <div class="col-xs-12"><strong><?php 
                                        if ($pregunta != $resultados['pregunta']){ 
                                            $pregunta = $resultados['pregunta'];
                                            $textoAd = "";
                                            $img = "";
                                            //busco a ver si la pregunta tiene respuestas del tipo radio_varios o radio_varios_imagen
                                            $bd2->select("SELECT * FROM encuesta_respuestas WHERE id_pregunta = ".$resultados['id_pregunta']." AND (tipo = 'radio_varios' OR tipo='radio_varios_imagen')");
                                            if ($bd2->numero_filas() > 0){
                                                $tipoPreg = $bd2->registro();
                                                if ($tipoPreg['tipo'] == 'radio_varios_imagen'){
                                                    $textoAd = "Del ".$tipoPreg['radio_desde']." al ".$tipoPreg['radio_hasta'];
                                                    $img = '<img src="../../../../portal_web/images/'.$tipoPreg['imagen'].'" /><br>';
                                                }else
                                                    $textoAd = "Del ".$tipoPreg['radio_desde']." (".$tipoPreg['texto_previo_desde'].") al ".$tipoPreg['radio_hasta']." (".$tipoPreg['texto_posterior_hasta'].")";
                                            }                                             
                                            echo $resultados['pregunta']." ".$textoAd.":".$img."<br>";
                                        }
                                        ?>
                                        </strong><label class="text-primary"><?php echo $resultados['valor']; ?></label></div>
                                </div>   
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>    
            <?php
        }
    } else {
        echo "<label class='label label-info'>Sin encuestas vinculadas</label>";
    }
    ?>
</div>               