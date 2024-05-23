<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = "Ver Rendición #{$model->idrendicion}";
$this->params['breadcrumbs'][] = $this->title;
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

    .alert-detalle {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
        max-height: 300px;
        overflow-y: auto;
    }
</style>

<div class="mds-rendicion-view">

    <?php if (!Yii::$app->request->isAjax) : ?>
        <header class="page-header">
            <h2><?= $this->title ?></h2>
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="index.php">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span><?= "Ver rendición #{$model->idrendicion}" ?></span></li>
                </ol>
                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
    <?php endif ?>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?= $this->render('components/flash_messages') ?>
                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <h5><b>Fecha de carga: </b><?= $model->fechaCarga ?></h5>
                                <?php if ($model->updated_at) { ?>
                                    <h5><b>Fecha de última modificación: </b><?= $model->fechaModifica ?></h5>
                                <?php } ?>
                            </div>
                            <div class="col-md-6 text-right">
                                <h5><b>Usuario de carga: </b><?= $model->idusuario_carga ? strtoupper($model->usuarioCarga->apellido) . ' ' . strtoupper($model->usuarioCarga->nombre) : "" ?></h5>
                                <?php if ($model->updated_at) { ?>
                                    <h5><b>Usuario de última modificación: </b><?= $model->idusuario_modifica ? strtoupper($model->usuarioModifica->apellido) . ' ' . strtoupper($model->usuarioModifica->nombre) : "" ?></h5>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="panel-group" id="accordion_datosRendicion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_datosRendicion" href="#datosRendicion">
                                        Datos de Rendición
                                    </a>
                                </h4>
                            </div>
                            <div id="datosRendicion" class="accordion-body collapse in">
                                <div class="panel-body" id="datosRendicion_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Tipo de Rendición</label>
                                            <input type="text" class="form-control" value="<?= $model->tipo->descripcion ?>" readonly>
                                        </div>
                                    </div>
                                    <?php if ($model->idpersona) { ?>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label">Tipo de Documento</label>
                                                <input type="text" class="form-control" value="<?= $model->persona->documentoTipo->descripcion ?>" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nro. de Documento</label>
                                                <input type="text" class="form-control" value="<?= $model->persona->documento ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Apellido y Nombre</label>
                                                <input type="text" class="form-control" value="<?= strtoupper($model->persona->apellido) ?> <?= strtoupper($model->persona->nombre) ?>" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if ($model->idusuario_comprobante) { ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Usuario</label>
                                                <input type="text" class="form-control" value="<?= strtoupper($model->usuarioComprobante->apellido) ?> <?= strtoupper($model->usuarioComprobante->nombre) . ' (' . $model->usuarioComprobante->dni . ')' ?>" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Sector</label>
                                            <input type="text" class="form-control" value="<?= $model->capa->descripcion ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Lugar</label>
                                            <input type="text" class="form-control" value="<?= $model->lugar->descripcion ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Monto Total</label>
                                            <input type="number" class="form-control" value="<?= $model->monto ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if ($model->fecha_comprobante) { ?>
                                                <label class="form-label">Fecha de factura/recibo/comprobante</label>
                                                <input type="text" class="form-control" value="<?= $model->fechaComprobante ?>" readonly>
                                            <?php } ?>
                                            <?php if ($model->fecha_vale) { ?>
                                                <label class="form-label">Fecha de entrega del vale</label>
                                                <input type="text" class="form-control" value="<?= $model->fechaVale ?>" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Observaciones</label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?= $model->observaciones;  ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if (count($adjuntos) > 0) : ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Documentación Adjunta</label>
                                                <ul style="list-style: none">
                                                    <?php
                                                    foreach ($adjuntos as $key => $adjunto) : ?>
                                                        <li>
                                                            <a><i class="fas fa-paperclip"></i> <?= Html::a($adjunto['nombre'], Url::base() . '/' . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <br>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <?php if ($comprobantes) : ?>
                            <div class="panel-group" id="accordion_datosComprobante">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_datosComprobante" href="#datosComprobante">
                                                Comprobantes
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="datosComprobante" class="accordion-body collapse in">
                                        <div class="panel-body" id="datosComprobante_content">
                                            <?php foreach ($model->comprobantes as $key => $model_comprobante) : ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h5 style="margin: 0"><b>Fecha de carga: </b><?= $model_comprobante->fechaCarga ?></h5>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        <h5><b>Usuario de carga: </b><?= $model_comprobante->idusuario_carga ? strtoupper($model_comprobante->usuarioCarga->apellido) . ' ' . strtoupper($model_comprobante->usuarioCarga->nombre) : "" ?></h5>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Fecha Desde</label>
                                                        <input type="text" class="form-control" value="<?= $model_comprobante->fechaDesde ?>" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Fecha Hasta</label>
                                                        <input type="text" class="form-control" value="<?= $model_comprobante->fechaHasta ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label">Observaciones</label>
                                                        <div class="alert alert-detalle" role="alert">
                                                            <p><?= $model_comprobante->observaciones;  ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $adjuntos_comprobante = $model_comprobante->getOtrosAdjuntos();
                                                if (count($adjuntos_comprobante) > 0) : ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label">Documentación Adjunta</label>
                                                            <ul style="list-style: none">
                                                                <?php
                                                                foreach ($adjuntos_comprobante as $key => $adjunto) : ?>
                                                                    <li>
                                                                        <a><i class="fas fa-paperclip"></i> <?= Html::a($adjunto['nombre'], Url::base() . '/' . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                            <br>
                                                        </div>
                                                    </div>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                    <?php if (!Yii::$app->request->isAjax) : ?>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-footer" id="botones">
                                    <a class="btn btn-info" href="index.php?r=mds_rendicion" title="Volver">Volver</a>
                                    <?php if ($model->idtipo == $TIPO_COMBUSTIBLE) : ?>
                                        <?= Html::button('Comprobante', ['id' => 'btn-rendicion-comprobante', 'type' => "button", 'class' => 'btn btn-info', 'data-toggle' => "modal", 'title' => 'Observar', 'data-target' => "#modalRendicionComprobante", 'data-idrendicion' => $model->idrendicion]); ?>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php

if ($model->idtipo == $TIPO_COMBUSTIBLE) :

    require(__DIR__ . '/modal_comprobante.php');

    $this->registerCssFile('@web/css/dropzone/dropzone.css');
    $this->registerJsFile('@web/js/dropzone/dropzone.js', ['position' => \yii\web\View::POS_END]);

    $this->registerJsFile('@web/js/dropzone/mds_legales_oficio/create.js', [
        'position' => \yii\web\View::POS_END
    ]);

    /*Se llama a la funcion js obtenerAdjuntos*/
    $paramAdjunto = "let adjuntos_oficio = ''";
    $this->registerJs($paramAdjunto, \yii\web\View::POS_END, 'obtenerOtrosAdjuntosOficio');

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

endif;

CrudAsset::register($this);

$this->registerJs("
    $('#btn-rendicion-comprobante').click(function() {
        let idrendicion = $(this).attr('data-idrendicion');
        $('#idrendicion').val(idrendicion);
    })
");
?>