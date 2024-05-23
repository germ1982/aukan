<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_direccion_usuario */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="mds-certificacion-direccion-usuario-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'idcertificaciondireccion')->widget(Select2::class, [
                'data' => $listDirecciones,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'id' => 'cmb_direccion'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ])->label('<b>Dirección/Área</b>');
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'idusuario')->widget(Select2::class, [
                'data' => $listUsuarios,
                'options' => [
                    'placeholder' => 'Seleccione...',
                    'id' => 'cmb_usuario'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ])->label('<b>Usuario</b>');
            ?>
        </div>

    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <a class="btn btn-info" href="index.php?r=mds_certificacion_direccion_usuario/index">Volver</a>
            <?= Html::submitButton('Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>