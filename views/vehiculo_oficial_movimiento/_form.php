<?php

use app\controllers\SiteController;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\VehiculoOficial;
use app\models\Empleado;


$model->fecha = $model->isNewRecord ? date('d/m/Y') : date('d/m/Y', strtotime($model->fecha));
$model->hora = $model->isNewRecord ? date('H:i') : date('H:i', strtotime($model->hora));


?>

<div class="vehiculo-oficial-movimiento-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_fecha($form, $model, 'fecha', 'fecha', 'Fecha') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_hora($form, $model, 'hora', 'input_hora', 'Hora') ?>
        </div>

        <div class="col-md-6">
            <?= SiteController::actionGet_input_select2($form, $model, 'idvehiculo', 'cmb_idvehiculo', VehiculoOficial::getVehiculosOficiales(), 'idvehiculo', 'descripcion', 'Vehiculo') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'lugar_salida')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'lugar_destino')->textInput() ?>
        </div>

    </div>

    <!-- Otros campos -->
    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'finalidad_viaje')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= SiteController::actionGet_input_select2($form, $model, 'chofer', 'cmb_chofer', Empleado::get_empleado_choferes(), 'idempleado', 'descripcion', 'Chofer') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'kilometraje')->textInput() ?>
        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>