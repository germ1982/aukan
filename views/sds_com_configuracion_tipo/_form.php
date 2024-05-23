<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_configuracion_tipo */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if (Yii::$app->session->hasFlash('success')) { ?>
    <div class="alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fas fa-ok"></i> ¡Excelente!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php } ?>
<?php if (Yii::$app->session->hasFlash('faild')) { ?>
    <div class="alert alert-danger alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		<h4><i class="icon fas fa-times"></i> ¡UPS!</h4>
        <?= Yii::$app->session->getFlash('faild') ?>
    </div>
<?php } ?>

<div class="sds-com-configuracion-tipo-form">

	<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top: 35px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => ($model->isNewRecord ? true : ($model->activo?true:false))]) ?>
        </div>
    </div>


	<?php if (!Yii::$app->request->isAjax) { ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	<?php } ?>

	<?php ActiveForm::end(); ?>

</div>