<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EdificioConectividad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="edificio-conectividad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idedificio')->textInput() ?>

    <?= $form->field($model, 'infraestructura')->textInput() ?>

    <?= $form->field($model, 'servicio')->textInput() ?>

    <?= $form->field($model, 'velocidad_en_mb')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo_conexion')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
