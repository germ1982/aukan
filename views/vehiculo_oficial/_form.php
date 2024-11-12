<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VehiculoOficial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vehiculo-oficial-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dominio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poliza')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'VTO')->textInput() ?>

    <?= $form->field($model, 'salida')->textInput() ?>

    <?= $form->field($model, 'llegada')->textInput() ?>

    <?= $form->field($model, 'lugar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hora')->textInput() ?>

    <?= $form->field($model, 'kilometraje')->textInput() ?>

    <?= $form->field($model, 'finalidad_viaje')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
