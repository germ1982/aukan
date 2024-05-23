<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_programaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-certificacion-programa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idcertificacionprograma') ?>

    <?= $form->field($model, 'iddireccion') ?>

    <?= $form->field($model, 'idprograma') ?>

    <?= $form->field($model, 'idusuario_carga') ?>

    <?= $form->field($model, 'idusuario_borra') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
