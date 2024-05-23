<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_ans_negativa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-ans-negativa-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'cuit')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'periodo')->textInput(['disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'fallecido')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?php
            if ($model->fecha_fallecido != null) {
                $model->fecha_fallecido = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_fallecido)));
            }
            echo $form->field($model, 'fecha_fallecido')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}',
                'options' => [
                    'id' => 'fecha_fallecido',
                    'class' => 'form-control input-md',
                    'disabled' => true
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ])->label('Fecha Fallecido'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'trabajador_dependiente')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'autonomo')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'monotributista')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'ddjprovincial')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'casas_particulares')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'efectores_sociales')->textInput(['disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'jubilado_pensionado')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'previsional_provincia')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'previsional_tramite')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'desempleo')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'programa_empleo')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'os_vigente')->textInput(['disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'asignacion_familiar')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'auh')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cuota_beca_progresar')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'beca_progresar')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'maternidad_casasparticulares')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'asignacion_familiar_jubilados')->textInput(['disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'pnc')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'iniciacion_pnc')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'aaff_discontinuos')->textInput(['disabled' => true]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="row" style="margin-top:5%;">
        <div class="col-md-2">
            <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
        </div>
    </div>
</div>