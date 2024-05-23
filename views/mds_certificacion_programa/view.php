<?php

use yii\widgets\ActiveForm;

$this->title = "Ver Registro #{$model->idcertificacionprograma}";
?>
<div class="mds-certificacion-programa-view">
    <?php $form = ActiveForm::begin(); ?>

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
                    <li><span><?= $this->title ?></span></li>
                </ol>

                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
    <?php endif ?>

    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Programa</b></label>
            <?php $programa = "{$model->programa0->descripcion}" ?>
            <input type="text" class="form-control" value="<?= $programa ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label"><b>Dirección</b></label>
            <input type="text" class="form-control" value="<?= $model->direccion0->direccion0->descripcion ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>¿Permitir cambio de responsable?</b></label>
            <input type="text" class="form-control" value="<?= $model->cambio_responsable !== null ? ($model->cambio_responsable == 0 ? 'No' : 'Si') : '' ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label"><b>Monto</b></label>
            <input type="number" class="form-control" value="<?= $model_certificacion_programa_monto ? $model_certificacion_programa_monto->monto : '' ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>¿Requiere autorización previa?</b></label>
            <input type="text" class="form-control" value="<?= $model->requiere_autorizacion !== null ? ($model->requiere_autorizacion == 0 ? 'No' : 'Si') : '' ?>" readonly>
        </div>
        <?php if ($model->requiere_autorizacion == 1) { ?>
            <div class="col-md-6">
                <label class="form-label"><b>Cantidad de niveles de autorización</b></label>
                <input type="text" class="form-control" value="<?= $model->cant_niveles_autorizacion !== null ? $model->cant_niveles_autorizacion : '' ?>" readonly>
            </div>
        <?php } ?>
    </div>
    <div class="row">

        <div class="col-md-6">
            <label class="form-label"><b>Tipo de subsidio</b></label>
            <input type="text" class="form-control" value="<?= $model->tipoSubsidio0 !== null ? $model->tipoSubsidio0->descripcion : '' ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Documentación obligatoria</b></label>
            <input type="text" class="form-control" value="<?= $selectAdjuntos ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label"><b>Documentación Sugerida</b></label>
            <input type="text" class="form-control" value="<?= $selectAdjuntosSugeridos ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Requisitos</b></label>
            <input type="text" class="form-control" value="<?= $selectRequisitos ?>" readonly>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) : ?>
        <br>
        <div class="card-footer" id="botones">
            <a class="btn btn-info" href="index.php?r=mds_certificacion_programa/index">Volver </a>
        </div>
    <?php endif ?>
    <?php ActiveForm::end(); ?>
</div>