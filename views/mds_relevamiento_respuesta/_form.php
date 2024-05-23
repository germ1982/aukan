<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_relevamiento_respuesta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-relevamiento-respuesta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idrelevamientoregistro')->textInput() ?>

    <?= $form->field($model, 'iditem')->textInput() ?>

    <?= $form->field($model, 'posee')->textInput() ?>

    <?= $form->field($model, 'detalle')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'idusuario_carga')->textInput() ?>

    <?= $form->field($model, 'idusuario_borra')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
