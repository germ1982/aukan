<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EdificioAcceso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="edificio-acceso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_edificio_acceso')->textInput() ?>

    <?= $form->field($model, 'idedificio')->textInput() ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
