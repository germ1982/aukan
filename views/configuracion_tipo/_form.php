<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionTipo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="configuracion-tipo-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-md-10">
			<?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-2" style="padding-top:30px;">
			<?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
		</div>

	</div>






	<?php if (!Yii::$app->request->isAjax) { ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	<?php } ?>

	<?php ActiveForm::end(); ?>

</div>