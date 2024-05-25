<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="persona-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'documento')->textInput() ?>

    <?= $form->field($model, 'documento_tipo')->textInput() ?>

    <?= $form->field($model, 'nacionalidad')->textInput() ?>

    <?= $form->field($model, 'genero')->textInput() ?>

    <?= $form->field($model, 'fecha_nacimiento')->textInput() ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'padre')->textInput() ?>

    <?= $form->field($model, 'conviviente')->textInput() ?>

    <?= $form->field($model, 'domicilio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domicilio_calle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domicilio_numero')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idlocalidad')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
