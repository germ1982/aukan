<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_acomp_asistenciaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-acomp-asistencia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idasistencia') ?>

    <?= $form->field($model, 'idusuario_carga') ?>

    <?= $form->field($model, 'idbeneficiario') ?>

    <?= $form->field($model, 'idlocalidad') ?>

    <?= $form->field($model, 'idlocalidad_ingreso') ?>

    <?php // echo $form->field($model, 'idriesgo') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <?php // echo $form->field($model, 'periodo_desde') ?>

    <?php // echo $form->field($model, 'periodo_hasta') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
