<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_rol */
?>

<div class="mds-seg-rol-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'idrol')->textInput(['maxlength' => true, 'readonly' => true])->label('<b>#</b>') ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true, 'readonly' => true])->label('<b>Descripción</b>') ?>
        </div>
        <div class="col-md-4">
            <?php
            if ($model->created_at != null) {
                $model->created_at = date('d/m/Y', strtotime(str_replace('/', '-', $model->created_at)));
            }
            echo $form->field($model, 'created_at')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}',
                'options' => [
                    'id' => 'created_at',
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
            ])->label('<b>Fecha de creación</b>'); ?>
        </div>
    </div>
    <?php if ($model->deleted_at) { ?>
        <div class="row">
            <div class="col-md-3">
                <?php
                if ($model->deleted_at != null) {
                    $model->deleted_at = date('d/m/Y', strtotime(str_replace('/', '-', $model->deleted_at)));
                }
                echo $form->field($model, 'deleted_at')->widget(DatePicker::ClassName(), [
                    'name' => 'check_issue_date',
                    'language' => 'es',
                    'readonly' => false,
                    'layout' => '{picker}{input}',
                    'options' => [
                        'id' => 'deleted_at',
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
                ])->label('<b>Fecha de borrado</b>'); ?>
            </div>
            <div class="col-md-5">
                <label class="form-label"><b>Usuario que borra</b></label>
                <input type="text" class="form-control" value="<?= $model->usuarioBorra->apellido ?> <?= $model->usuarioBorra->nombre ?>" readonly>
            </div>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>