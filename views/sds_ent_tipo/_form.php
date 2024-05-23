<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_tipo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-ent-tipo-form">

	<?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-12">
			<?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3 col-md-offset-7" style="text-align: left;">
			<?= $form->field($model, 'tiene_numero')->checkbox() ?>
		</div>
		<div class="col-md-2" style="text-align: left;">
			<?= $form->field($model, 'activo')->checkbox() ?>
		</div>
	</div>

	<?php if (!Yii::$app->request->isAjax) { ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	<?php } ?>

	<?php ActiveForm::end(); ?>

</div>