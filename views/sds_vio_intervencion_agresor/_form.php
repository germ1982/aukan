<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_intervencion_agresor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-vio-intervencion-agresor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idintervencion')->textInput() ?>

    <?= $form->field($model, 'idagresor')->textInput() ?>

    <?= $form->field($model, 'parentezco')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
