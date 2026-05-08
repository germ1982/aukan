<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="configuracion-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form_configuracion',
    ]); ?>

    <?= Html::activeHiddenInput($model, 'id_configuracion_tipo') ?>

    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>