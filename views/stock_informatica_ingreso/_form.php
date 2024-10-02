<?php

use app\controllers\SiteController;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model->fecha = date('d/m/Y', strtotime($model->fecha));
?>


    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'idusuario_carga')->hiddenInput(['id' => 'input_idusuario_carga'])->label(false) ?>
    <div class="row">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'fecha', 'Fecha') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'idempleado_recepcion')->textInput() ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'idorigen')->textInput() ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'origen_referencia')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

