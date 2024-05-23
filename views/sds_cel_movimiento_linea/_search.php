<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_movimiento_lineaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-cel-movimiento-linea-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idmovimientolinea') ?>

    <?= $form->field($model, 'fecha_hora') ?>

    <?= $form->field($model, 'idusuario') ?>

    <?= $form->field($model, 'solicitante') ?>

    <?= $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'responsable_anterior') ?>

    <?php // echo $form->field($model, 'responsable_nuevo') ?>

    <?php // echo $form->field($model, 'equipo_anterior') ?>

    <?php // echo $form->field($model, 'equipo_nuevo') ?>

    <?php // echo $form->field($model, 'organismo_anterior') ?>

    <?php // echo $form->field($model, 'organismo_nuevo') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <?php // echo $form->field($model, 'idlinea') ?>

    <?php // echo $form->field($model, 'adjunto') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
