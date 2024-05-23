<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_gis_capa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-certificacion-estado-form">

	<?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-md-12">
			<?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-12" style="padding-top: 35px;">
			<?=
			$form->field($model, 'deleted_at', ['labelOptions' => ['style' => 'font-weight:bolder']])->dropdownList(
				[
					1 => "Si",
					0 => "No"
				]
			)
			?>
		</div>
	</div>
	<?php if (!Yii::$app->request->isAjax) { ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	<?php } ?>

	<?php ActiveForm::end(); ?>

</div>