<?php

use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;

?>
<style>
    .btnPrint {
        background: grey !important;
    }

    .btnPrint a {
        color: white !important;
        text-decoration: none !important;
    }
</style>
<header class="page-header">
    <h2>Relevamiento #<?= $model->idrelevamientoregistro ?> - <?= $model->capaitem->descripcion ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Relevamiento #<?= $model->idrelevamientoregistro ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="mds-relevamiento-registro-form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
            </section>
            <div class="panel-body">
                <?php echo Collapse::widget([]); ?>
                <div class="panel-group">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            </h4>
                        </div>
                        <div class="accordion-body collapse in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label"><b>Edificio</b></label>
                                        <input type="text" class="form-control" value='<?= $model->capaitem->descripcion ?>' readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><b>Fecha</b></label>
                                        <input type="text" class="form-control" value="<?php
                                                                                        $fa = date_create($model->fecha);
                                                                                        $fa = date_format($fa, 'd-m-Y');
                                                                                        echo $fa ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php foreach ($agrupadores as $agrupador) { ?>
                    <div class="panel-group">
                        <div class="panel panel-accordion" id="accordion_<?= $agrupador['descripcion'] ?>">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_<?= $agrupador['descripcion'] ?>" href="#detalle_<?= $agrupador['descripcion'] ?>">
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                        <?= $agrupador['titulo'] ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_<?= $agrupador['descripcion'] ?>" class="accordion-body collapse <?= $model->isNewRecord ? '' : 'in' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="col-md-12">
                                        <?php foreach ($model_respuesta as $item) { ?>
                                            <?php if ($agrupador['idconfiguraciontipo'] == $item['idconfiguraciontipo']) { ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
                                                        <b><?= $item['descripcion'] ?></b>
                                                        <input type="text" class="form-control" value="<?php echo $item['posee'] === null ? '' : ($item['posee'] == 1 ? 'Si' : 'No') ?>" readonly>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <label><b>Detalle</b></label>
                                                        <input type="text" class="form-control" value="<?php echo $item['detalle'] === null ? '' : $item['detalle'] ?>" readonly>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="panel-group">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Observaciones
                                </a>
                            </h4>
                        </div>
                        <div class="accordion-body collapse in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea name="textarea" class="form-control" rows="10" readonly><?= $model->observaciones ?></textarea>
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
                                    <?php if (count($adjuntos) > 0) : ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul style="list-style: none">
                                                    <?php
                                                    foreach ($adjuntos as $key => $adjunto) : ?>
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
                <a class="btn btn-info" href="index.php?r=mds_relevamiento_registro/index">Volver</a>
                |
                <button type="button" class="btn btnPrint">
                    <?php $url =  Url::to(['/mds_relevamiento_registro/detalle_registro', 'idrelevamientoregistro' => $model->idrelevamientoregistro]); ?>
                    <a href="<?= $url ?>" target="_blank" title="Exportar PDF">Exportar PDF</a>
                </button>
            </div>
        </div>
    </div>
</div>