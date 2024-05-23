<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_registroSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-reproam-registro-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idregistro') ?>

    <?= $form->field($model, 'numero_legajo_reproam') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'direccion') ?>

    <?= $form->field($model, 'idbarrio') ?>

    <?php // echo $form->field($model, 'idlocalidad') ?>

    <?php // echo $form->field($model, 'telefono') ?>

    <?php // echo $form->field($model, 'idzona') ?>

    <?php // echo $form->field($model, 'nombre_presidente') ?>

    <?php // echo $form->field($model, 'nombre_vicepresidente') ?>

    <?php // echo $form->field($model, 'nombre_secretario') ?>

    <?php // echo $form->field($model, 'personeria_juridica') ?>

    <?php // echo $form->field($model, 'personeria_juridica_numero') ?>

    <?php // echo $form->field($model, 'personeria_juridica_resolucion') ?>

    <?php // echo $form->field($model, 'personeria_juridica_fecha_vencimiento') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
