<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RegistroRecepcion */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .file-drop-zone {
        min-height: 100px !important;
    }

    .file-preview-image {
        min-height: 100px !important;
        max-width: 100% !important;
        /* Ajusta la imagen al 100% del contenedor */
        max-height: 100% !important;
        /* Define la altura máxima de la vista previa */
        object-fit: cover !important;
        /* Cubre el contenedor sin distorsión */

    }

    .krajee-default {
        min-height: 100px !important;
        float: none !important;
    }

    .kv-file-content {
        min-height: 100px !important;
        width: 100% !important;
    }
</style>

<div class="registro-recepcion-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class=" col-md-4">
            <?= $form->field($model, 'fecha')->textInput() ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'hora')->textInput() ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'dni')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class=" col-md-4">
            <?= $form->field($model, 'motivo')->textarea() ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'acceso')->textInput() ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'id_dispositivo_derivacion')->textInput() ?>
        </div>
    </div>
    <div class=" row">
        <div class=" col-md-4">
            <?= $form->field($model, 'id_responsable_derivacion')->textInput() ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'id_tipo_recepcion')->textInput() ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
        </div>

    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    

    <?php ActiveForm::end(); ?>

</div>