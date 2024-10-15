<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Automotores */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="automotores-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idempleado')->textInput() ?>

    <?= $form->field($model, 'idpersona')->textInput() ?>

    <?= $form->field($model, 'dominio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idmarca')->textInput() ?>

    <?= $form->field($model, 'modelo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vehiculo_oficial')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
