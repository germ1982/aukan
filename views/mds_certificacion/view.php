<?php

use yii\helpers\Html;
use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\Mds_certificacion;

$this->title = "Ver certificación #{$model->idcertificacion} de {$model->beneficiario->apellido} {$model->beneficiario->nombre}";
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;

CrudAsset::register($this);

?>
<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }

    .btnPrint {
        background: grey !important;
    }

    .btnBaja {
        color: white !important;
        background: grey !important;
    }

    .btnRechaza {
        color: white !important;
        background: red !important;
    }

    .btnObserva {
        color: white !important;
        background: orange !important;
    }

    .btnPrint a {
        color: white !important;
        text-decoration: none !important;
    }

    .alert-detalle {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
        max-height: 300px;
        overflow-y: auto;
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
            <li><span><?= "Ver certificación #{$model->idcertificacion}" ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-certificacion-view">
                    <div class="panel-group" id="accordion_estado">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_estado" href="#estado">
                                        Estado actual
                                    </a>
                                </h4>
                            </div>
                            <div id="estado" class="accordion-body collapse in">
                                <div class="panel-body" id="estado_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php switch ($model->idestado) {
                                                case $listadoPosiblesEstados['ESTADO_PENDIENTE']:
                                                    $style = 'alert alert-info';
                                                    break;
                                                case $listadoPosiblesEstados['ESTADO_APROBADA']:
                                                    $style = 'alert alert-success';
                                                    break;
                                                case $listadoPosiblesEstados['ESTADO_OBSERVADA']:
                                                    $style = 'alert alert-warning ';
                                                    break;
                                                case $listadoPosiblesEstados['ESTADO_RECHAZADA']:
                                                    $style = 'alert alert-danger';
                                                    break;
                                                case $listadoPosiblesEstados['ESTADO_ENVIADA']:
                                                    $style = 'alert alert-info';
                                                    break;
                                                case $listadoPosiblesEstados['ESTADO_BAJA']:
                                                    $style = 'alert alert-primary';
                                                    break;
                                                case $listadoPosiblesEstados['ESTADO_ELIMINADA']:
                                                    $style = 'alert alert-dark';
                                                    break;
                                            }
                                            ?>
                                            <div class="<?= $style ?>">
                                                <h5><?= $model_certificacion_estado['estado'] ?> por <?= $model_certificacion_estado['usuario'] ?> el <?= $model_certificacion_estado['created_at'] ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php
                                            echo Html::button('<i class="fas fa-eye"></i>', [
                                                'id' => 'btnEstados',
                                                'tabIndex' => '-1',
                                                'onclick' => '
                                                            const idcertificacion= $("#idcertificacion").val();
                                                            $("#modal_estados").modal("show")
                                                            .find("#content_modal_estados")
                                                            .load("index.php?r=mds_certificacion/ver_estados&idcertificacion="+idcertificacion);
                                                ',
                                                'title' => 'Ver estados anteriores'
                                            ]);
                                            ?>
                                            <span style="margin-left: 5px">
                                                Estados anteriores
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <?php
                                        if ($model_certificacion_estado['fecha'] != '') : ?>
                                            <div class="col-md-6">
                                                <label class="form-label"><b>Fecha de baja</b></label>
                                                <input type="text" class="form-control" value="<?= $model_certificacion_estado['fecha']  ?  $model_certificacion_estado['fecha'] :  null ?>" readonly>
                                            </div>
                                        <?php endif ?>
                                        <?php
                                        if (count($adjuntosEspeciales) > 0) : ?>
                                            <div class="col-md-6">
                                                <label><b>Documentación adjunta</b></label>
                                                <ul style="list-style-type: circle">
                                                    <?php
                                                    foreach ($adjuntosEspeciales as $key => $adjunto) : ?>
                                                        <li>
                                                            <a><i class="fas fa-paperclip"></i> <?= Html::a($adjunto['nombre'], Url::base() . '/' . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <br>
                                            </div>
                                        <?php endif ?>
                                        <?php if ($model_certificacion_estado['observaciones'] != '') : ?>
                                            <div class="col-md-12">
                                                <label class="form-label"><b>Motivo</b></label>
                                                <textarea name="observaciones" id="observaciones" class="form-control" rows="2" readonly><?= $model_certificacion_estado['observaciones'] ?></textarea>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_beneficiario">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_beneficiario" href="#beneficiario">
                                        Beneficiario
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="beneficiario" class="accordion-body collapse in">
                                <div class="panel-body" id="beneficiario_content">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Tipo de documento</label>
                                            <input type="text" class="form-control" value="DNI" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="hidden" id="idcertificacionSelected" name="idcertificacionSelected" value="<?= $model->idcertificacion ?>">
                                            <label class="form-label">Persona</label>
                                            <?php $beneficiario = $model->beneficiario->apellido . " " . $model->beneficiario->nombre . " (" . $model->beneficiario->documento . ")" ?>
                                            <input type="text" class="form-control" value="<?= strtoupper($beneficiario) ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Localidad</label>
                                            <input type="text" class="form-control" value="<?= $model->localidad->descripcion ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_asistencia">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_asistencia" href="#asistencia">
                                        Asistencia
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="asistencia" class="accordion-body collapse in">
                                <div class="panel-body" id="asitencia_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Equipo Técnico</label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?= $model->equipo_tecnico;  ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Periodo desde</label>
                                            <input type="text" class="form-control" value="<?= $model->periodo_desde ? date('d/m/Y', strtotime(str_replace('/', '-', $model->periodo_desde))) :  null ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Periodo hasta</label>
                                            <input type="text" class="form-control" value="<?= $model->periodo_hasta ? date('d/m/Y', strtotime(str_replace('/', '-', $model->periodo_hasta))) :  null ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Área</label>
                                            <input type="text" class="form-control" value="<?= $model->area->direccion0->descripcion ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Programa</label>
                                            <input type="text" class="form-control" value="<?= $model->programa->descripcion ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Nivel de autorización</label>
                                            <input type="text" class="form-control" value="<?= $model->idnivel_autorizacion ? $model->nivelAutorizacion->descripcion : '' ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <?php $certif = $model->idcertificacion ?>
                                            <label class="form-label">Dirección</label>
                                            <input type="text" class="form-control" value="<?= $model->direccion ? $model->direccion->direccion0->descripcion : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Monto Solicitado</label>
                                            <input type="number" class="form-control" value="<?= $model_certificacion_monto->monto ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <?php
                                            echo Html::button('<i class="fas fa-eye"></i>', [
                                                'tabIndex' => '-1',
                                                'onclick' => '
                                                            const idcertificacion= $("#idcertificacion").val();
                                                            $("#modal_montos").modal("show")
                                                            .find("#content_modal")
                                                            .load("index.php?r=mds_certificacion/ver_montos&idcertificacion="+idcertificacion);
                                                ',
                                                'title' => 'Ver montos anteriores',
                                                'style' => 'margin-top:25px;',
                                            ]);
                                            ?>
                                            <span>
                                                Ver montos anteriores
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Cáracter</label>
                                            <input type="text" class="form-control" value="<?= $model->caracter->descripcion ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if ($model->id_certificacion_incremento) { ?>
                                                <label class="form-label">Certificación que se incrementa</label>
                                                <input type="text" class="form-control" value="#<?= $model->id_certificacion_incremento ? $model->incremento->idcertificacion . '-Desde ' . date('d/m/Y', strtotime(str_replace('/', '-', $model->incremento->periodo_desde))) . ' al ' . date('d/m/Y', strtotime(str_replace('/', '-', $model->incremento->periodo_hasta))) : '' ?>" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Nro Expediente</label>
                                            <input type="text" class="form-control" value="<?= $model->nro_expediente ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Nro Nota</label>
                                            <input type="text" class="form-control" value="<?= $model->nro_nota ?>" readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Tipo Certificación</label>
                                            <input type="text" class="form-control" value="<?= $model->tipo_certificacion === null ? '' : ($model->tipo_certificacion == 1 ? 'EXTERNA' : 'INTERNA') ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if ($model->tipo_certificacion == 1) { ?>
                                                <label class="form-label">Organismo Solicitante</label>
                                                <input type="text" class="form-control" value="<?= $model->organismoSolicitante ? $model->organismoSolicitante->descripcion : '' ?>" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">¿Recibe jubilación/pensión?</label>
                                            <input type="text" class="form-control" value="<?= $model->jubilacion === null ? '' : ($model->jubilacion == 1 ? 'Sí' : 'No')  ?>" readonly>
                                        </div>
                                        <?php if ($model->jubilacion == 1) { ?>
                                            <div class="col-md-3">
                                                <label class="form-label">Tipo de jubilación/pensión</label>
                                                <input type="text" class="form-control" value="<?= $model->tipoJubilacion->descripcion ?>" readonly>
                                            </div>
                                        <?php } ?>
                                        <?php if ($model->monto_jubilacion) { ?>
                                            <div class="col-md-3">
                                                <label class="form-label">Monto Neto de la jubilación/pensión</label>
                                                <input type="number" class="form-control" value="<?= $model->monto_jubilacion ?>" readonly>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">¿Recibe sueldo?</label>
                                            <input type="text" class="form-control" value="<?= $model->sueldo === null ? '' : ($model->sueldo == 1 ? 'Sí' : 'No')  ?>" readonly>
                                        </div>
                                        <?php if ($model->sueldo == 1) { ?>
                                            <div class="col-md-3">
                                                <label class="form-label">Monto Neto del sueldo</label>
                                                <input type="number" class="form-control" value="<?= $model->sueldo_monto ?>" readonly>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_responsable">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_responsable" href="#responsable">
                                        Responsable de cobro/Tutor especial
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="responsable" class="accordion-body collapse in">
                                <div class="panel-body" id="llamante_content">
                                    <div class="row">
                                        <div id="ver_responsable_btn" class="col-md-12">
                                            <input type="hidden" id="idcertificacion" name="idcertificacion" value="<?= $model->idcertificacion ?>">
                                            <?php
                                            $url =  Url::to(['/mds_certificacion/historial_responsables', 'idcertificacion' => $model->idcertificacion]);
                                            echo  Html::a('<span style="margin-left: 0.5rem" class="fas fa-user-alt"></span>', $url, [
                                                'role' => 'modal-remote',
                                                'title' => 'Historial responsables',
                                                'data-toggle' => 'tooltip'
                                            ]);
                                            ?>
                                            <span style="margin-left: 10px">
                                                Ver historial de responsables
                                            </span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">¿Es Curador/Tutor Legal?</label>
                                            <input type="text" class="form-control" value="<?= $model_responsable->curador_legal === null ? '' : ($model_responsable->curador_legal == 1 ? 'Sí' : 'No')  ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tipo de responsable de cobro/Tutor especial</label>
                                            <input type="text" class="form-control" value="<?= $model_responsable->tipo_responsable === null ? '' : ($model_responsable->tipoResponsable->descripcion) ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">¿Debe presentar la rendición?</label>
                                            <input type="text" class="form-control" value="<?= $model_responsable->rendicion === null ? '' : ($model_responsable->rendicion == 1 ? 'Sí' : 'No')  ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Nombre y Apellido</label>
                                            <input type="text" class="form-control" value="<?= strtoupper($model_responsable->nombre_apellido) ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">DNI</label>
                                            <input type="text" class="form-control" value="<?= $model_responsable->dni ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">CBU/Alias</label>
                                            <input type="text" class="form-control" value="<?= $model_responsable->cbu_alias ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Parentesco</label>
                                            <input type="text" class="form-control" value="<?= $model_responsable->idparentesco ? $model_responsable->parentesco0->descripcion : '' ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if ($model_responsable->idparentesco == $PARENTESCO_OTRO_OPTION) { ?>
                                                <label class="form-label">Parentesco otro</label>
                                                <input type="text" class="form-control" value="<?= $model_responsable ? $model_responsable->parentesco_otro : '' ?>" readonly>
                                            <?php } ?>
                                        </div>
                                    </div><br>
                                    <?php if ($infoResponsable) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="txt_info_responsable" class="alert alert-info">
                                                    <span id="txt_mensaje_responsable"><?= $infoResponsable ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_obs">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_obs" href="#obs">
                                        Observaciones
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="obs" class="accordion-body collapse in">
                                <div class="panel-body" id="adjuntos_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?= $model->observaciones;  ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_adjuntos">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_adjuntos" href="#adjuntos">
                                        Documentación Adjunta
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="adjuntos" class="accordion-body collapse in">
                                <div class="panel-body" id="adjuntos_content">
                                    <div class="row">
                                        <?php
                                        if (count($adjuntos) > 0) : ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <ul style="list-style: none">
                                                        <?php
                                                        foreach ($adjuntos as $key => $adjunto) : ?>
                                                            <?php if ($key === 0) : ?>
                                                                <p> <?= $adjunto['tipoAdjunto'] ?> </p>
                                                                <ul>
                                                                <?php endif; ?>
                                                                <?php if ($key - 1 > -1 && $adjuntos[$key - 1]['tipoAdjunto'] !==  $adjunto['tipoAdjunto']) : ?>
                                                                </ul>
                                                                <p> <?= $adjunto['tipoAdjunto'] ?> </p>
                                                                <ul>
                                                                <?php endif; ?>
                                                                <li>
                                                                    <a><i class="fas fa-paperclip"></i> <?= Html::a($adjunto['nombre'], Url::base() . '/' . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a>
                                                                </li>
                                                                <?php if (count($adjuntos) == 1) : ?>
                                                                </ul>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <br>
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vistos">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vistos" href="#vistos">
                                        Vistos
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <?php
                            if (!empty($model->vistos)) : ?>
                                <div id="vistos" class="accordion-body collapse in">
                                    <div class="panel-body" id="vistos_content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5><b>Visto por:</b></h5>
                                                <ul>
                                                    <?php foreach ($model->vistos as $visto) :
                                                        $usuarioNombre = mb_strtoupper($visto->usuario['apellido']) . ', ' . mb_strtoupper($visto->usuario['nombre']);
                                                        $fechaCarga = date('d/m/Y H:i', strtotime($visto['fecha_carga'])); ?>
                                                        <li>
                                                            <?= "$fechaCarga - <b>$usuarioNombre</b>" ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-footer" id="botones">
                                <a class="btn btn-info" href="index.php?r=mds_certificacion&area=<?= $area; ?>" title="Volver">Volver</a>
                                |
                                <button type="button" class="btn btnPrint">
                                    <?php $url =  Url::to(['/mds_certificacion/certificacion_detalle', 'idcertificacion' => $model->idcertificacion, 'area' => $area]); ?>
                                    <a href="<?= $url ?>" target="_blank" title="Exportar PDF">Exportar PDF</a>
                                </button>
                                |
                                <button data-toggle="modal" href="#modal_visto" id="BOTON_VISTO" data-idcertificacion="<?= $model->idcertificacion; ?>" class="btn btn-primary">Marcar visto</button>
                                <?php if ($permissionAction['permissionEnviar']) : ?>
                                    <?= Html::button('Enviar', ['id' => 'boton-enviar', 'type' => "button", 'class' => 'btn btn-success btnEnviar', 'data-toggle' => "modal", 'title' => 'Enviar', 'data-target' => "#modal_enviar"]); ?>
                                <?php endif ?>
                                <?php if ($permissionAction['permissionAprobar']) : ?>
                                    <?= Html::button('Aprobar', ['id' => 'boton-aprobar', 'type' => "button", 'class' => 'btn btn-success btnAprueba', 'data-toggle' => "modal", 'title' => 'Aprobar', 'data-target' => "#modal_aprobar"]); ?>
                                <?php endif ?>
                                <?php if ($permissionAction['permissionObservar']) : ?>
                                    <?= Html::button('Observar', ['id' => 'boton-observar', 'type' => "button", 'class' => 'btn btn-warning btnObserva', 'data-toggle' => "modal", 'title' => 'Observar', 'data-target' => "#modal_observar"]); ?>
                                <?php endif ?>
                                <?php if ($permissionAction['permissionRechazar']) : ?>
                                    <?= Html::button('Rechazar', ['id' => 'boton-rechazar', 'type' => "button", 'class' => 'btn btn-danger btnRechaza', 'data-toggle' => "modal", 'title' => 'Rechazar', 'data-target' => "#modal_rechazar"]); ?>
                                <?php endif ?>
                                <?php if ($permissionAction['permissionBaja']) : ?>
                                    <?= Html::button('Dar de baja', ['id' => 'boton-baja', 'type' => "button", 'class' => 'btn btn-default btnBaja', 'data-toggle' => "modal", 'title' => 'Baja', 'data-target' => "#modal_baja"]); ?>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<div class="modal fade" id="modal_enviar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-archivo">Enviar certificación <b> #<?= $model->idcertificacion ?> </b> </h4>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['mds_certificacion/actualizarestado'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="modal-body">
                <div class="row" style="padding:15px">
                    <input type="hidden" name="idcertificacion_para_actualizar" id="idcertificacion_para_actualizar" value="<?= $model->idcertificacion ?>">
                    <input type="hidden" name="estado" id="estado" value="enviar">
                    <input type="hidden" name="sector" id="sector" value="<?= $area ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <p>¿Está seguro que desea Enviar la certificación de <?= $model->beneficiario->apellido . " " . $model->beneficiario->nombre . " (" . $model->beneficiario->documento . ")"  ?> ? </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="No, cancelar">No, cancelar</button>
                <?= Html::submitButton("Si, enviar", ['class' => 'btn btn-success', 'title' => 'Si, enviar']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_aprobar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-archivo">Aprobar certificación <b> #<?= $model->idcertificacion ?> </b></h4>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['mds_certificacion/actualizarestado'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="modal-body">
                <div class="row" style="padding:15px">
                    <input type="hidden" name="idcertificacion_para_actualizar" id="idcertificacion_para_actualizar" value="<?= $model->idcertificacion ?>">
                    <input type="hidden" name="estado" id="estado" value="aprobar">
                    <input type="hidden" name="sector" id="sector" value="<?= $area ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <p>¿Está seguro que desea aprobar la certificación de <b><?= $model->beneficiario->apellido . " " . $model->beneficiario->nombre . " (" . $model->beneficiario->documento . ")</b>" ?>? </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="No, cancelar">No, cancelar</button>
                <?= Html::submitButton("Si, aprobar", ['class' => 'btn btn-success', 'title' => 'Si, aprobar']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_rechazar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-archivo"><b>Rechazar certificación <b> #<?= $model->idcertificacion ?> </b></b></h4>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['mds_certificacion/actualizarestado'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="modal-body">
                <div class="row" style="padding:15px" id="modal_content_rechazar">
                    <p>¿Está seguro que desea rechazar la certificación de <b><?= $model->beneficiario->apellido . " " . $model->beneficiario->nombre . " (" . $model->beneficiario->documento . ")"  ?></b>?</p>
                    <p>Motivo:</p>
                    <input type="hidden" name="idcertificacion_para_actualizar" id="idcertificacion_para_actualizar" value="<?= $model->idcertificacion ?>">
                    <input type="hidden" name="estado" id="estado" value="rechazar">
                    <input type="hidden" name="sector" id="sector" value="<?= $area ?>">
                    <div class="row">
                        <div class="col-md-12" class="form-group">
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="No, cancelar">No, cancelar</button>
                <?= Html::submitButton("Si, rechazar", ['class' => 'btn btn-danger', 'title' => 'Si, rechazar']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_observar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-archivo"><b>Observar certificación #<?= $model->idcertificacion ?> </b></h4>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['mds_certificacion/actualizarestado'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="modal-body">
                <div class="row" style="padding:15px" id="modal_content_observar">
                    <b>Beneficiario: <?= $model->beneficiario->apellido . " " . $model->beneficiario->nombre . " (" . $model->beneficiario->documento . ")"  ?></b>
                    <p>Detalle:</p>
                    <input type="hidden" name="idcertificacion_para_actualizar" id="idcertificacion_para_actualizar" value="<?= $model->idcertificacion ?>">
                    <input type="hidden" name="estado" id="estado" value="observar">
                    <input type="hidden" name="sector" id="sector" value="<?= $area ?>">
                    <div class="row">
                        <div class="col-md-12" class="form-group">
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="No, cancelar">No, cancelar</button>
                <?= Html::submitButton("Si, observar", ['class' => 'btn btn-warning btnObserva', 'id' => 'btnObservar', 'title' => 'Si, observar']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_baja" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-archivo">Dar de baja certificación <b> #<?= $model->idcertificacion ?> </b></h4>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['mds_certificacion/actualizarestado'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="modal-body">
                <div class="row" style="padding:15px" id="modal_content_baja">
                    <div class="col-md-12 form-group">
                        <p>¿Está seguro que desea dar de baja la certificación de <b><?= $model->beneficiario->apellido . " " . $model->beneficiario->nombre . " (" . $model->beneficiario->documento . ")"  ?></b>?</p>
                        <p>Programa: <?= $model->programa->descripcion ?></p>
                        <input type="hidden" name="idcertificacion_para_actualizar" id="idcertificacion_para_actualizar" value="<?= $model->idcertificacion ?>">
                        <input type="hidden" name="estado" id="estado" value="baja">
                        <input type="hidden" name="sector" id="sector" value="<?= $area ?>">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Fecha de baja</label>
                        <input type="date" class="form-control" id="fecha" name="fecha">
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="form-label">Motivo:</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="6"></textarea>
                    </div>
                    <div class="col-md-12 form-group" id="dropzone_modal">
                        <br>
                        <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                        <input type="hidden" id="TIPO_ADJUNTO">
                        <div class="adjuntar-text" style="display: flex; justify-content: flex-end"><i class="fa fa-upload"></i> Adjuntar archivos
                        </div>
                        <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                        </div>
                        <small class="text-muted" style="display: flex; justify-content: flex-end">La extension debe ser del tipo
                            ["pdf,jpeg,jpg,png,xls,xlsx"]. Tamaño máximo 50MB.</small>
                        <br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="No, cancelar">No, cancelar</button>
                <?= Html::submitButton("Si, dar de baja", ['class' => 'btn btnBaja', 'title' => 'Si, dar de baja']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
require 'modal_visto.php';

Modal::begin([
    'id' => 'modal_responsables',
    'header' => "<h4 class='class-title' id='titulo-modal-archivo'>Cambios de responsables de certificación <b> #{$model->idcertificacion} </b> </h4>",
    'size' => 'modal-lg'
]);
echo "<div id='content_modal'></div>";
Modal::end();

Modal::begin([
    'id' => 'modal_estados',
    'header' => "<h4 class='class-title' id='titulo-modal-archivo'>Estados anteriores de certificación <b> #{$model->idcertificacion} </b> </h4>",
    'size' => 'modal-lg'
]);
echo "<div id='content_modal_estados'></div>";
Modal::end();

Modal::begin([
    'id' => 'modal_montos',
    'header' => "<h4 class='class-title' id='titulo-modal-archivo'>Montos de certificación <b> #{$model->idcertificacion}, {$model->beneficiario->apellido} {$model->beneficiario->nombre}</b> </h4>",
    'size' => 'modal-fade'
]);
echo "<div id='content_modal'></div>";
Modal::end();

$this->registerCssFile('@web/css/dropzone/dropzone.css');
$this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', ['position' => \yii\web\View::POS_END]);

/*Se llama a la funcion js obtenerAdjuntos*/
$paramAdjunto = "let adjuntos_oficio = ''";
$this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');

$this->registerJs(
    "$(document).ready(function() {
        clickModal();
    })
    
    $('#BOTON_VISTO').click(function(){
        const idCertificacion = $(this).data('idcertificacion');
        $('#idcertificacion_visto').val(idCertificacion);
   });
    "
);
?>
<script>
    let divToMove = document.getElementById('dropzone_modal');

    function clickModal() {
        $('#boton-observar').click(function(e) {
            eliminarDropzone();
            let modalContainerObservar = document.getElementById('modal_content_observar');
            modalContainerObservar.appendChild(divToMove);
            $('#TIPO_ADJUNTO').val('<?= $ADJUNTO_OBSERVAR ?>');
        });

        $('#boton-rechazar').click(function(e) {
            eliminarDropzone();
            let modalContainerRechazar = document.getElementById('modal_content_rechazar');
            modalContainerRechazar.appendChild(divToMove);
            $('#TIPO_ADJUNTO').val('<?= $ADJUNTO_RECHAZAR ?>');
        });

        $('#boton-baja').click(function(e) {
            eliminarDropzone();
            let modalContainerBaja = document.getElementById('modal_content_baja');
            modalContainerBaja.appendChild(divToMove);
            $('#TIPO_ADJUNTO').val('<?= $ADJUNTO_BAJA ?>');
        });
    }

    function eliminarDropzone() {

        let modalContainerObservar = document.getElementById('modal_content_observar');
        let modalContainerRechazar = document.getElementById('modal_content_rechazar');
        let modalContainerBaja = document.getElementById('modal_content_baja');

        let hijoObservar = modalContainerObservar.querySelector('#dropzone_modal');
        let hijoRechazar = modalContainerRechazar.querySelector('#dropzone_modal');
        let hijoBaja = modalContainerBaja.querySelector('#dropzone_modal');

        Dropzone.forElement('#adjunto-otrosdocumentos').removeAllFiles(true);
        $("#adjunto-otrosdocumentos").find(".dz-message").text("Adjunte documentos complementarios aqui");

        if (hijoObservar) hijoObservar.remove();
        if (hijoRechazar) hijoRechazar.remove();
        if (hijoBaja) hijoBaja.remove();
    }
</script>