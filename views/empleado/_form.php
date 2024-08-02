<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="empleado-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idpersona')->textInput() ?>

    <?= $form->field($model, 'iddispositivo')->textInput() ?>

    <?= $form->field($model, 'legajo')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'foto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'activo')->textInput() ?>

    <?= $form->field($model, 'categoria')->textInput() ?>

    <?= $form->field($model, 'antiguedad_legal')->textInput() ?>

    <?= $form->field($model, 'antiguedad_total')->textInput() ?>

    <?= $form->field($model, 'ingreso_real')->textInput() ?>

    <?= $form->field($model, 'ingreso_administrativo')->textInput() ?>

    <?= $form->field($model, 'contratacion')->textInput() ?>

    <?= $form->field($model, 'cuil')->textInput() ?>

    <?= $form->field($model, 'funcion')->textInput() ?>

    <?= $form->field($model, 'fichado')->textInput() ?>

    <?= $form->field($model, 'afiliacion')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
