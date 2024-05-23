<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_pad_padron */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-pad-padron-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'circuito_anterior')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'circuito_nuevo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'denominacion_circuito')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'afiliacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'documento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'calle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'altura')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
