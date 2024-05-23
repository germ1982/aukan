<?php

use app\models\Mds_legales_oficio;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_capacitacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Derivar requerimiento #{$oficio->idlegalesoficio}";
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;
$rolRegistro = $oficio->tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO);
$rolSupervisor = $oficio->tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);

/*Si la derivacion original sigue activa y tengo el rol de supervisor es porque rechazo el generador de respuestas 
Aclaracion: 
Como la derivacion original siempre es la que hizo el registro al supervisor entonces:
    Si rechaza el generador de respuestas: el activo no se modifica (sigue en 1). 
    Si rechaza el supervisor, el activo se modifica (pasa a ser 0).
    De esta forma definimos si el re-derivar es de registro o supervisor
*/
$esRederivarSupervisor = $rolSupervisor && $derivacionOriginal->activo == 1; //True: El que esta re-derivando es el supervisor - False: El que esta re-derivando es el registro
CrudAsset::register($this);

?>

<style>
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
            <div class="panel-body">
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
                <br />
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
                        if (!empty($supervisores)) :
                        ?>
                            <ul>
                                <?php foreach ($supervisores as $supervisor) :
                                    $año = substr($supervisor['fecha_derivacion'], 2, 2);
                                    $mes = substr($supervisor['fecha_derivacion'], 5, 2);
                                    $dia = substr($supervisor['fecha_derivacion'], 8, 2);
                                    $hora = substr($supervisor['fecha_derivacion'], 11, 5);
                                    $fecha = "<span class='text-muted'>$dia/$mes/$año $hora</span>";
                                    $apellidoMayuscula = mb_strtoupper($supervisor->usuario->apellido);
                                    $nombreMayuscula = mb_strtoupper($supervisor->usuario->nombre);
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
                        if (!empty($derivacionesReceptores)) :
                        ?>
                            <ul>
                                <?php foreach ($derivacionesReceptores as $derivacion) :
                                    $año = substr($derivacion['fecha_derivacion'], 2, 2);
                                    $mes = substr($derivacion['fecha_derivacion'], 5, 2);
                                    $dia = substr($derivacion['fecha_derivacion'], 8, 2);
                                    $hora = substr($derivacion['fecha_derivacion'], 11, 5);
                                    $fecha = "<span class='text-muted'>$dia/$mes/$año $hora</span>";
                                    $apellidoMayuscula = mb_strtoupper($derivacion->usuario->apellido);
                                    $nombreMayuscula = mb_strtoupper($derivacion->usuario->nombre);
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
                        if (!empty($usuariosRechazo)) :
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

                <br />
                <?php
                $oficioAdjunto = $oficio->getAdjuntosByTipo('oficio');
                if (!empty($oficioAdjunto)) { ?>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Adjunto requerimiento:</label>
                            <ul style="list-style: none">
                                <?php foreach ($oficioAdjunto as $adjunto) : ?>
                                    <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, \yii\helpers\Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <br />
                <?php }
                $otrosAdjuntos = $oficio->getAdjuntosByTipo('otros');
                if (!empty($otrosAdjuntos)) : ?>
                    <label>Otros documentos:</label>
                    <ul style="list-style: none">
                        <?php foreach ($otrosAdjuntos as $adjunto) : ?>
                            <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, \yii\helpers\Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <br />
                <?php endif ?>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['action' => ['mds_legales_oficio/rederivarstore'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" name="idlegalesoficio" value="<?php echo $oficio->idlegalesoficio  ?>">
                <input type="hidden" id="rolSupervisor" value="<?= $rolSupervisor ?>">
                <input type="hidden" id="esRederivarSupervisor" value="<?= $esRederivarSupervisor ?>">
                <?php $derviacionesLength = count($derivaciones);
                if ($derviacionesLength > 0) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <ul>
                                    <!-- SI EL OFICIO FUE RECHAZADO POR RECEPCION -->
                                    <?php foreach ($derivaciones as $index => $derivacion) : ?>
                                        <li><b><?php echo date('d/m/Y H:i', strtotime(str_replace('/', '-', $derivacion->fecha_usu_no_corresponde))) . " - " ?></b><strong> <?php echo mb_strtoupper($derivacion->usuario->apellido) . ", " . mb_strtoupper($derivacion->usuario->nombre) . (($derivacion->supervisor == 1) ? " (<u>Supervisor/a</u>) " : " (<u>Generador/a de respuestas</u>)") . " devolvió el requerimiento por el siguiente motivo: <span style='color: #777; font-weight: normal;'>" . $derivacion->observaciones . "</span>" ?></strong></li>
                                        <?php
                                        $devolucionAdjuntos = $oficio->getAdjuntosByTipo('devolucion', $derivacion->idlegalesderivacion, 'mds_legales_derivacion');
                                        if (count($devolucionAdjuntos) > 0) : ?>
                                            <label>Archivos adjuntos:</label>
                                            <ul style="list-style: none">
                                                <?php
                                                foreach ($devolucionAdjuntos as $adjunto) :
                                                    if ($derivacion->idlegalesderivacion == $adjunto->id_objeto) :
                                                ?>
                                                        <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif ?>
                                        <?php if ($index !== ($derviacionesLength - 1)) : ?>
                                            <hr />
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($rolRegistro || count($oficio->getReceptoresRechazo()) > 0) : ?>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="col-md-12 alert alert-info" role="alert">
                                <p>Por favor vuelva a re-derivar a los usuarios que corresponda (ya se precarga los usuarios que no hayan rechazado). <b>Recuerde que debe volver a presionar el botón "Derivar"</b>.</p>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <!-- Lo que visualiza el supervisor -->
                <?php if ($esRederivarSupervisor) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Derivación (Responsable de generar respuestas)</label>
                            <?=
                            Select2::widget([
                                'name' => 'users',
                                'id' => 'users',
                                'value' => $arrayUsuariosReceptoresDerivados,
                                'data' => $arrayUsuariosReceptores,
                                'options' => ['multiple' => true, 'required' => true, 'placeholder' => 'Seleccione responsables'],
                                'showToggleAll' => false,
                            ]);

                            ?>
                        </div>
                    </div>
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
                        <div class="alert alert-info" role="alert" style="margin-top: 15px;">
                            <label><strong> <?= ($sugerenciaFecha) ? "$sugerenciaFecha - " : "" ?> Última observación / instrucción del supervisor: <?= ($sugerenciaApellidoMayuscula) ? "$sugerenciaApellidoMayuscula, $sugerenciaNombreMayuscula" : '' ?></strong></label>

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
                            <br>
                            <?= $form->field($oficio, 'sugerencia')->widget(\bizley\quill\Quill::class, [
                                'allowResize' => true,
                                'options' => [
                                    'style' => 'height: 125px;',
                                    'id' => 'texto_sugerencia_id',
                                ],
                            ]) ?>
                        </div>
                    </div>
                <?php else : ?>
                    <!-- Lo que visualiza registro -->
                    <div class="row">

                        <div class="col-md-12">
                            <label class="form-label">Nueva observación (se añadirá debajo de la observación anterior)</label>
                            <textarea class="form-control" id="nuevaObservacion" name="Mds_legales_oficio[nuevaObservacion]" style="min-height: 30vh"></textarea>
                        </div>

                        <div class="col-md-12" style="margin-top: 1rem;">
                            <?= $form->field($oficio, 'idarea')->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::find('idconfiguracion', 'descripcion')->where(['=', 'idconfiguraciontipo', Sds_com_configuracion_tipo::LEGALES_AREA_TIPO])->andWhere(['=', 'activo', 1])->orderBy(['descripcion' => SORT_ASC])->all(),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'id' => 'idarea',
                                    'name' => 'idarea',
                                    'placeholder' => 'Seleccionar el área a derivar...',
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                    'onchange' => 'precargarSupervisores()',
                                ]
                            )->label('Derivación a:')
                            ?>
                        </div>

                        <div class="col-md-12">
                            <label>Supervisores/as (responsable de supervisar respuestas)</label>
                            <?=
                            Select2::widget([
                                'name' => 'supervisores',
                                'id' => 'supervisores',
                                'value' => $arraySupervisoresDerivados,
                                'data' => $arraySupervisores,
                                'options' => ['multiple' => true, 'required' => true, 'placeholder' => 'Seleccione responsables'],
                                'showToggleAll' => false,
                            ]);

                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <label><strong>Archivos Adjuntos</strong></label>
                        <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                        <input type="hidden" id="adjuntos_eliminados" name="Mds_legales_oficio[adjuntos_eliminados]">
                        <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=mds_legales_oficio/index">Volver </a>
                    <?php if ($derivacionSupervision) {
                        echo Html::a('<span class="btn-label">Devolver</span>', \yii\helpers\Url::to(['mds_legales_oficio/rechazaroficio', 'idDerivacion' => $derivacionSupervision->idlegalesderivacion]), ['class' => 'btn btn-danger']);
                    }
                    ?>
                    <?= Html::submitButton("Derivar", ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
</div>

<script>
    function precargarSupervisores() {
        const supervisoresByArea = JSON.parse('<?= $supervisoresByArea ?>');
        const idArea = $("#idarea").val();
        const supervisoresByAreaFiltrado = supervisoresByArea.filter(supervisor => supervisor.idarea == idArea);
        const arrayIdSupervisoresASeleccionar = supervisoresByAreaFiltrado.map(supervisor => supervisor.idusuario);
        $("#supervisores").val(arrayIdSupervisoresASeleccionar).trigger("change");
    }
</script>

<?php
require 'modal_devoluciones.php';
require 'modal_supervisores.php';
require 'modal_generadores_respuesta.php';

$this->registerJs(
    "$(document).ready(function() {  
        $('#btnSave').click(function(e){
            const users =  $('#users').val();
            const supervisores =  $('#supervisores').val();
            const rolSupervisor = $('#rolSupervisor').val();
            
            if ((!users || !users.length) && (!supervisores || !supervisores.length)){
                alert('Debe seleccionar a los responsables o supervisores');
                e.preventDefault();
            } else {
                const esRederivarSupervisor = $('#esRederivarSupervisor').val();
                const sugerencia_adjuntos = $('#otros_adjuntos').val();
                if (sugerencia_adjuntos && sugerencia_adjuntos !== '[]') {
                    const texto_sugerencia =  $('#texto_sugerencia_id').val();

                    const parser = new DOMParser();
                    const { textContent } = parser.parseFromString(texto_sugerencia, 'text/html').documentElement;
                    textoSugerenciaSinHTML = textContent.trim();

                    if (esRederivarSupervisor && (!texto_sugerencia || texto_sugerencia.length < 1 || !textoSugerenciaSinHTML) && rolSupervisor) {
                        /*La sugerencia no puede ser vacias si se adjunta un archivo porque si no se escribe una sugerencia
                        no se muestra el div que tiene toda la info de la misma. 
                        Entonces si se adjunta un archivo y no se escribe una sugerencia, no se veria el archivo adjunto al estar oculto el div.
                        */
                        alert('Las observaciones / instrucciones no deben estar vacías si se adjuntan archivos.');
                        e.preventDefault();
                    }
                }
            }
        })
    });

    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    })
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


$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);

$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
    'position' => \yii\web\View::POS_END
]);
if ($rolSupervisor) {
    $adjuntos = \yii\helpers\Json::encode($oficio->getAdjuntosByTipo('sugerencia'));
} else { // Registro
    $adjuntos = \yii\helpers\Json::encode($oficio->getAdjuntosByTipo('otros'));
}

/*Se llama a la funcion js obtenerAdjuntos*/
$paramAdjunto = "let adjuntos =''; let adjuntos_oficio = '$adjuntos'";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerAdjuntos');


function ordenarByApellido($a, $b)
{
    return strcmp(strtoupper($a["usuario"]["apellido"]), strtoupper($b["usuario"]["apellido"]));
}
