<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_gerontologiaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-gerontologia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idgerontologia') ?>

    <?= $form->field($model, 'fecha_atencion') ?>

    <?= $form->field($model, 'idpersona') ?>

    <?= $form->field($model, 'idobrasocial') ?>

    <?= $form->field($model, 'domicilio') ?>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
