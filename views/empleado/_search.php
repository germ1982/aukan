<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EmpleadoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="empleado-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idempleado') ?>

    <?= $form->field($model, 'idpersona') ?>

    <?= $form->field($model, 'iddispositivo') ?>

    <?= $form->field($model, 'legajo') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'telefono') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'activo') ?>

    <?php // echo $form->field($model, 'categoria') ?>

    <?php // echo $form->field($model, 'antiguedad_legal') ?>

    <?php // echo $form->field($model, 'antiguedad_total') ?>

    <?php // echo $form->field($model, 'ingreso_real') ?>

    <?php // echo $form->field($model, 'ingreso_administrativo') ?>

    <?php // echo $form->field($model, 'contratacion') ?>

    <?php // echo $form->field($model, 'cuil') ?>

    <?php // echo $form->field($model, 'funcion') ?>

    <?php // echo $form->field($model, 'fichado') ?>

    <?php // echo $form->field($model, 'afiliacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
