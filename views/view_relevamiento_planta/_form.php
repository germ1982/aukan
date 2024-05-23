<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\View_relevamiento_planta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="view-relevamiento-planta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'relevado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ultima_modificacion')->textInput() ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'documento')->textInput() ?>

    <?= $form->field($model, 'legajo')->textInput() ?>

    <?= $form->field($model, 'Cuil')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organismo_funciones_actualmente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Categoría')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lugar_planta_permanente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_ingreso')->textInput() ?>

    <?= $form->field($model, 'fecha_nacimiento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'funcion_actual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
