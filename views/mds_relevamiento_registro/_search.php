<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_relevamiento_registroSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-relevamiento-registro-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idrelevamientoregistro') ?>

    <?= $form->field($model, 'idcapaitem') ?>

    <?= $form->field($model, 'observaciones') ?>

    <?= $form->field($model, 'idusuario_carga') ?>

    <?= $form->field($model, 'idusuario_borra') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
