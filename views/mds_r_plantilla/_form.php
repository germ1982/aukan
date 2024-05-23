<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_plantilla */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-r-plantilla-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'variable_diagnostico')->textInput() ?>

    <?= $form->field($model, 'idtipoplantilla')->textInput() ?>

    <?= $form->field($model, 'dimension')->textInput() ?>

    <?= $form->field($model, 'origen')->textInput() ?>

    <?= $form->field($model, 'fechahoracreate')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
