<?php

use app\models\Mds_conc_solicitud;
use yii\widgets\ActiveForm;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/* @var $this yii\web\View */
/* @var $model app\models\Mds_conc_historial */
?>

<div class="mds-conc-historial-view">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-2 form-group">
            <label>Fecha</label>
            <input type="text" class="form-control" value="<?= $fecha ? $fecha : '' ?>" readonly />
        </div>
        <div class="col-md-2 form-group">
            <label>Hora</label>
            <input type="text" class="form-control" value="<?= $hora ? $hora : '' ?>" readonly />
        </div>
        <div class="col-md-4 form-group">
            <label>Estado Anterior</label>
            <input type="text" class="form-control" value="<?= $model->anteriorEstado ? $model->anteriorEstado->descripcion : '' ?>" readonly />
        </div>
        <div class="col-md-4 form-group">
            <label>Estado Anterior</label>
            <input type="text" class="form-control" value="<?= $model->nuevoEstado ? $model->nuevoEstado->descripcion : '' ?>" readonly />
        </div>
        <div class="col-md-12 form-group">
            <label>Usuario carga</label>
            <input type="text" class="form-control" value="<?= $model->usuarioCarga ? mb_strtoupper("{$model->usuarioCarga->nombre} {$model->usuarioCarga->apellido}") : '' ?>" readonly />
        </div>
        <div class="col-md-12 form-group" style="display: <?= $model->estado_nuevo == Mds_conc_solicitud::ESTADO_SELECCIONADO || $model->estado_nuevo == Mds_conc_solicitud::ESTADO_ADMITIDO  ? '' : 'none' ?>">
            <?= $form->field($model->postulacion0, 'puntaje')->textInput(['maxlength' => true, "readOnly" => true]); ?>
        </div>
        <div class="col-md-12 form-group" style="display: <?= ($model->estado_nuevo == Mds_conc_solicitud::ESTADO_NO_ADMITIDO || $model->estado_nuevo == Mds_conc_solicitud::ESTADO_IMPUGNADO) && $motivosImpugnacion ? '' : 'none' ?>">
            <label>Motivo de impugnación</label>
            <textarea class="form-control" readonly rows="5"><?= $motivosImpugnacion ? $motivosImpugnacion : '' ?></textarea>
        </div>
        <div class="col-md-12 form-group">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6, 'disabled' => true]); ?>
        </div>
        <div class="col-md-12 form-group">
            <?= $form->field($model, 'observacion_publica')->textarea(['rows' => 6, 'disabled' => true]); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>