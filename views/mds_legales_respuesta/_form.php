<?php

use app\models\Mds_legales_oficio;
use app\models\Mds_legales_respuesta_estado;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_capacitacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Respuesta a requerimiento #' . $oficio->idlegalesoficio;
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;
$idRolReceptor = Mds_legales_oficio::ID_ROL_RECEPTOR;
$estadoRechazada = Mds_legales_respuesta_estado::RECHAZADA;

CrudAsset::register($this);

?>

<style>
    .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
        color: black !important;
    }

    /* Se corrige placeholder que se ve sobre el menu */
    .select2-search {
        position: static !important;
    }

    .primer-oficio {
        margin-top: 34px;
    }

    .overflow-y {
        max-height: 450px;
        overflow-y: auto;
    }

    .boton-modal-default {
        display: inline-block;
        margin-bottom: 0;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        line-height: 1.42857143;
        border-radius: 4px;
        border-color: #adadad;
    }

    .boton-modal-default:hover {
        color: #333;
        background-color: #e6e6e6;
    }

    .alert-observaciones {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
        max-height: 300px;
        overflow-y: auto;
    }

    @media screen and (max-width: 991px) {
        .encargado-margin {
            margin-bottom: 1rem;
        }

        .primer-oficio {
            margin: 10px 0;
        }
    }
</style>


<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-heading">Requerimiento #<?php echo $oficio['idlegalesoficio'] ?></div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['action' => ['mds_legales_respuesta/store'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" id="adjuntos" name="Mds_legales_respuesta[adjuntos]">
                <input type="hidden" id="adjuntos_eliminados" name="adjuntos_eliminados">
                <input type="hidden" name="idlegalesoficio" value="<?php echo $oficio->idlegalesoficio  ?>">
                <input type="hidden" name="idrespuestacorreccion" value="<?php echo  Yii::$app->getRequest()->getQueryParam('idrespuesta')  ?>">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="form-label">Emisor órgano superior</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->emisor->descripcion ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Entidad requirente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->lugar_libramiento ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Localidad</label>
                        <input type="text" class="form-control" id="donde_se_tramita" value="<?php echo $oficio->donde_tramita ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="form-label">Responsable de la entidad requirente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->doctor_a_cargo ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha recepción</label>

                        <input type="text" class="form-control" value="<?php echo $oficio->fecha_recepcion ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_recepcion))) :  null ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha requerimiento</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->fecha_oficio ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_oficio))) :  null ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="form-label">Carátula</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratulaModel ? $oficio->caratulaModel->caratula : '' ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Personas vinculadas</label>
                        <?php if ($oficio->dni_legajo_vinculado) : ?>
                            <textarea class="form-control" rows="3" readonly><?php echo $oficio->dni_legajo_vinculado ?></textarea>
                            <br>
                        <?php endif; ?>
                        <?= $oficio->listapersonasvinculadas; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="form-label">Plazo (días)</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->tiempo_respuesta ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha vencimiento</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->fecha_plazo ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_plazo))) :  null ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="form-label">Número expediente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratulaModel ? $oficio->caratulaModel->numero_expediente : '' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Caso</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratulaModel ? $oficio->caratulaModel->caso : '' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Año</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratulaModel ? $oficio->caratulaModel->anio_expediente : '' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Número trámite / cédula / oficio</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->tramite_simple ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="form-label">Motivo de solicitud</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->motivo_solicitud ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Providencia</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->providencia ?>" readonly>
                    </div>
                    <div class="col-md-3 primer-oficio">
                        <input type="checkbox" id="primer_oficio" <?= $oficio->primer_oficio == 1 ? 'checked' : '' ?> disabled> <label for="primer_oficio">Es primer requerimiento</label>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo de requerimiento</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->tipoOficio->descripcion ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label class="form-label">Derivación a:</label>
                        <input type="text" class="form-control" value="<?php echo ($oficio->areaOficio) ? $oficio->areaOficio->descripcion : '' ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Observaciones</label>
                        <div class="alert alert-observaciones" role="alert">
                            <p><?= $oficio->observaciones; ?></p>
                        </div>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-md-3 encargado-margin">
                        <label class="form-label"><strong>Requerimiento creado por:</strong></label>
                        <?php
                        $fechaCarga = $oficio['fecha_carga'];
                        $año = substr($fechaCarga, 2, 2);
                        $mes = substr($fechaCarga, 5, 2);
                        $dia = substr($fechaCarga, 8, 2);
                        $hora = substr($fechaCarga, 11, 5);
                        $fecha = "<span class='text-muted'>$dia/$mes/$año $hora</span>";
                        $apellidoMayuscula = mb_strtoupper($oficio->usuario->apellido);
                        $nombreMayuscula = mb_strtoupper($oficio->usuario->nombre);
                        ?>
                        <ul>
                            <li><?= "$fecha - $apellidoMayuscula, $nombreMayuscula" ?></li>
                        </ul>
                    </div>
                    <div class="col-md-3 encargado-margin">
                        <label class="form-label"><strong><button id="boton-supervisores" type="button" class="boton-modal-default" data-toggle="modal" data-target="#modalSupervisores">Encargados/as de supervisar respuestas:</button></strong></label>
                        <?php
                        $supervisores = $oficio->getSupervisores();
                        usort($supervisores, "ordenarByApellido");
                        if (count($supervisores) > 0) :
                        ?>
                            <ul>
                                <?php foreach ($supervisores as $supervisor) :
                                    $apellidoMayuscula = mb_strtoupper($supervisor->usuario->apellido);
                                    $nombreMayuscula = mb_strtoupper($supervisor->usuario->nombre);
                                    $año = substr($supervisor['fecha_derivacion'], 2, 2);
                                    $mes = substr($supervisor['fecha_derivacion'], 5, 2);
                                    $dia = substr($supervisor['fecha_derivacion'], 8, 2);
                                    $hora = substr($supervisor['fecha_derivacion'], 11, 5);
                                    $fecha = "<span class='text-muted'>$dia/$mes/$año $hora</span>";
                                ?>
                                    <li><?= "$fecha - $apellidoMayuscula, $nombreMayuscula" ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php else : ?>
                            <p>No existen derivaciones activas</p>
                        <?php endif ?>
                    </div>
                    <div class="col-md-3 encargado-margin">
                        <label class="form-label"><strong><button id="boton-generadores-respuesta" type="button" class="boton-modal-default" data-toggle="modal" data-target="#modalGeneradoresRespuesta">Encargados/as de generar respuestas:</button></strong></label>
                        <?php
                        $derivacionesReceptores = $oficio->getReceptores();
                        usort($derivacionesReceptores, "ordenarByApellido");
                        if (count($derivacionesReceptores) > 0) :
                        ?>
                            <ul>
                                <?php foreach ($derivacionesReceptores as $derivacion) :
                                    $apellidoMayuscula = mb_strtoupper($derivacion->usuario->apellido);
                                    $nombreMayuscula = mb_strtoupper($derivacion->usuario->nombre);
                                    $año = substr($derivacion['fecha_derivacion'], 2, 2);
                                    $mes = substr($derivacion['fecha_derivacion'], 5, 2);
                                    $dia = substr($derivacion['fecha_derivacion'], 8, 2);
                                    $hora = substr($derivacion['fecha_derivacion'], 11, 5);
                                    $fecha = "<span class='text-muted'>$dia/$mes/$año $hora</span>";
                                ?>
                                    <li><?= "$fecha - $apellidoMayuscula, $nombreMayuscula" ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php else : ?>
                            <p>No existen derivaciones activas</p>
                        <?php endif ?>
                    </div>
                    <div class="col-md-3 encargado-margin">
                        <label class="form-label"><strong><button id="boton-devoluciones" type="button" class="boton-modal-default" data-toggle="modal" data-target="#modalDevoluciones">Devuelto por:</button></strong></label>
                        <?php
                        $usuariosRechazo = $oficio->getUsuariosDerivacionRechazo();
                        if (count($usuariosRechazo) > 0) :
                        ?>
                            <ul>
                                <?php foreach ($usuariosRechazo as $usuarioRechazo) :
                                    $fechaCarga = $usuarioRechazo['fecha_usu_no_corresponde'];
                                    $anio = substr($fechaCarga, 2, 2);
                                    $mes = substr($fechaCarga, 5, 2);
                                    $dia = substr($fechaCarga, 8, 2);
                                    $hora = substr($fechaCarga, 11, 5);
                                    $fecha = "<span class='text-muted'>$dia/$mes/$anio $hora</span>";
                                    $tipoUsuario = '';
                                    if ($usuarioRechazo['supervisor'] == 0) {
                                        $tipoUsuario = '<b>Generador/a de respuestas</b>';
                                    } else {
                                        $tipoUsuario = '<b>Supervisor/a</b>';
                                    }
                                    $apellidoMayuscula = mb_strtoupper($usuarioRechazo->usuario->apellido);
                                    $nombreMayuscula = mb_strtoupper($usuarioRechazo->usuario->nombre);
                                ?>
                                    <li><?= "$fecha - $apellidoMayuscula, $nombreMayuscula -  $tipoUsuario" ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php else : ?>
                            <p>No hubo devoluciones</p>
                        <?php endif ?>
                    </div>
                </div>

                <?php
                $oficioAdjunto = $oficio->getAdjuntosByTipo('oficio');
                if (count($oficioAdjunto) > 0) : ?>
                    <br>
                    <label>Adjunto Requerimiento</label>
                    <ul style="list-style: none">
                        <?php
                        foreach ($oficioAdjunto as $adjunto) : ?>
                            <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif ?>
                <br>
                <?php
                $otrosAdjuntos = $oficio->getAdjuntosByTipo('otros');
                ?>
                <?php
                if (count($otrosAdjuntos) > 0) : ?>
                    <label>Otros archivos</label>
                    <ul style="list-style: none">
                        <?php
                        foreach ($otrosAdjuntos as $adjunto) : ?>
                            <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <br>
                <?php endif ?>

                <?php
                if ($oficio->sugerencia) : ?>
                    <?php
                    $sugerenciaApellidoMayuscula = ($oficio->sugerenciaUsuario) ? mb_strtoupper($oficio->sugerenciaUsuario->apellido) : '';
                    $sugerenciaNombreMayuscula = ($oficio->sugerenciaUsuario) ? mb_strtoupper($oficio->sugerenciaUsuario->nombre) : '';
                    $sugerenciaFecha = $oficio['sugerencia_fecha'];
                    if ($sugerenciaFecha) {
                        $sugerenciaAnio = substr($sugerenciaFecha, 2, 2);
                        $sugerenciaMes = substr($sugerenciaFecha, 5, 2);
                        $sugerenciaDia = substr($sugerenciaFecha, 8, 2);
                        $sugerenciaHora = substr($sugerenciaFecha, 11, 5);
                        $sugerenciaFecha = "$sugerenciaDia/$sugerenciaMes/$sugerenciaAnio $sugerenciaHora";
                    }
                    ?>
                    <div class="alert alert-info" role="alert">
                        <label><strong> <?= ($sugerenciaFecha) ? "$sugerenciaFecha - " : "" ?> Observaciones / instrucciones del supervisor: <?= ($sugerenciaApellidoMayuscula) ? "$sugerenciaApellidoMayuscula, $sugerenciaNombreMayuscula" : '' ?></strong></label>

                        <p>
                            <?= $oficio->sugerencia ?>
                        </p>
                        <br>

                        <?php
                        $sugerenciaAdjuntos = $oficio->getAdjuntosByTipo('sugerencia');
                        if (count($sugerenciaAdjuntos) > 0) : ?>
                            <label>Archivos adjuntos:</label>
                            <ul style="list-style: none">
                                <?php
                                foreach ($sugerenciaAdjuntos as $adjunto) : ?>
                                    <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php if ($respuestaObservada) {
                            $respuesta_supervisorAdjuntos = $respuestaObservada->getAdjuntosRespuestaSupervisor();
                            $htmlAdjuntosSupervisor = '';
                            if (count($respuesta_supervisorAdjuntos) > 0) {
                                $htmlAdjuntosSupervisor = "<label>Archivos adjuntos por supervisor/a:</label><ul style='list-style: none'>";
                                foreach ($respuesta_supervisorAdjuntos as $adjunto) {
                                    $htmlAdjuntosSupervisor .= "<li><a><i class='fas fa-paperclip'></i>" . Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) . "</a></li>";
                                }
                                $htmlAdjuntosSupervisor .= "</ul>";
                            }
                        } ?>
                        <!-- SI ES UNA RESPUESTA DESDE UNA OBSERVACION -->
                        <?php if ($respuestaObservada != null && $respuestaObservada->ultimoEstado->estado == Mds_legales_respuesta_estado::OBSERVADA) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php
                                $respuestaRechazada = $respuestaObservada->getUltimaRespuestaEstadoByEstadoId($estadoRechazada);
                                if ($respuestaRechazada) : ?>
                                    <h5><strong><?= date('d/m/Y H:i', strtotime($respuestaRechazada->fecha_inicio)) . ' - '  ?>El Equipo de Supervisión Final (<?= "{$respuestaRechazada->usuario->apellido}, {$respuestaRechazada->usuario->nombre}" ?>) devolvió una respuesta por el siguiente motivo:</strong></h5>
                                    <p><?= $respuestaRechazada->observaciones ?></p>
                                    <hr />
                                <?php endif; ?>
                                <h5><strong><?= date('d/m/Y H:i', strtotime($respuestaObservada->ultimoEstado->fecha_inicio)) . ' - '  ?>El supervisor/a <?= "{$respuestaObservada->ultimoEstado->usuario->apellido}, {$respuestaObservada->ultimoEstado->usuario->nombre}" ?> realizó una observación por el siguiente motivo:</strong></h5>
                                <p><?= $respuestaObservada->ultimoEstado->observaciones ?></p>
                                <?php if ($htmlAdjuntosSupervisor) : ?>
                                    <br>
                                    <?= $htmlAdjuntosSupervisor ?>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                        <?php if ($respuestaObservada != null) {
                            $model->texto_repuesta = $respuestaObservada->texto_repuesta;
                        } ?>
                        <div style="margin-top: 1rem">
                            <?= $form->field($model, 'texto_repuesta')->widget(\bizley\quill\Quill::class, [
                                // 'allowResize' => true,
                                'options' => [
                                    'style' => 'height: 125px;',
                                    'id' => 'texto_repuesta_id',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Agentes intervinientes en la respuesta</label>
                        <?= Select2::widget([
                            'name' => 'profesionales',
                            'id'  => 'profesionales',
                            'value' => ($respuestaObservada) ? ArrayHelper::map($respuestaObservada->getProfesionalesIntervinientes(), 'idusuario', function ($model) {
                                return $model->idusuario;
                            }) : '',
                            'data' => ArrayHelper::map(
                                $listaProfesionales,
                                'idusuario',
                                function ($model) {
                                    $fullName = mb_strtoupper($model->apellido) . " " . mb_strtoupper($model->nombre);
                                    return $fullName;
                                }
                            ),
                            'options' => ['placeholder' => 'Seleccione agente...', 'multiple' => true],
                            'showToggleAll' => false,
                        ]); ?>
                    </div>
                </div>
                <label><strong>Archivos adjuntos respuesta</strong></label>
                <div>
                    <div class="dropzone needsclick dz-clickable" id="adjuntos" name="mainFileUploader">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </div>
                </div>


                <br>
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=mds_legales_oficio/index">Volver </a>
                    <?php if (count($oficio->getLastRespuestasEstadoByEstado($estadoRechazada)) === 0) : ?>
                        <a class="btn btn-danger" href="index.php?r=mds_legales_oficio/rechazaroficio&idDerivacion=<?= $derivacionOriginal->idlegalesderivacion ?>" role="post" data-pjax="0" data-toggle="tooltip">Devolver </a>
                    <?php endif; ?>
                    <?= Html::submitButton("Responder", ['class' => 'btn btn-success', 'id' => 'btnResponder']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </section>
    </div>
</div>

<?php
require __DIR__ . '../../mds_legales_oficio/modal_devoluciones.php';
require __DIR__ . '../../mds_legales_oficio/modal_supervisores.php';
require __DIR__ . '../../mds_legales_oficio/modal_generadores_respuesta.php';

$this->registerJs(
    "
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    })

    $(document).ready(function() {  
        $('#btnResponder').click(function(e){
            const texto_repuesta =  $('#texto_repuesta_id').val();

            const parser = new DOMParser();
            const { textContent } = parser.parseFromString(texto_repuesta, 'text/html').documentElement;
            textoRespuestaSinHTML = textContent.trim();

            if (!texto_repuesta || texto_repuesta.length < 1 || !textoRespuestaSinHTML){
                alert('La respuesta no debe estar vacía');
                e.preventDefault();
            }
        })
    });
    "
);

Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]);

Modal::end();

function ordenarByApellido($a, $b)
{
    return strcmp(strtoupper($a["usuario"]["apellido"]), strtoupper($b["usuario"]["apellido"]));
}
?>