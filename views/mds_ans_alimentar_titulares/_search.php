<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\mds_ans_alimentar_titularesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-ans-alimentar-titulares-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'apellido') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'cuil') ?>

    <?= $form->field($model, 'provincia') ?>

    <?php // echo $form->field($model, 'municipio') ?>

    <?php // echo $form->field($model, 'totalHijos') ?>

    <?php // echo $form->field($model, 'embarazo') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'localidad') ?>

    <?php // echo $form->field($model, 'departamento') ?>

    <?php // echo $form->field($model, 'dni') ?>

    <?php // echo $form->field($model, 'estado_entrega') ?>

    <?php // echo $form->field($model, 'fecha_hora') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
