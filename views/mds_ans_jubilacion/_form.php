<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_ans_jubilacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-ans-jubilacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tipo_dni')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dni')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cuil')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'beneficio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'periodo')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
