<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDecreto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organismo-decreto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'periodo_inicio')->textInput() ?>

    <?= $form->field($model, 'periodo_final')->textInput() ?>

    <?= $form->field($model, 'periodo_prorroga')->textInput() ?>

    <?= $form->field($model, 'activo')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
