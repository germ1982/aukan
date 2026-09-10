<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Backend */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="backend-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'endpoint')->textInput(['maxlength' => true]) ?>

    <div class=" col-md-4" style="padding-top:30px;">
        <?= $form->field($model, 'estado')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->estado]) ?>
    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>