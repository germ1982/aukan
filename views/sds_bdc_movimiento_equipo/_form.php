<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_movimiento_equipo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-bdc-movimiento-equipo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idmovimiento')->textInput() ?>

    <?= $form->field($model, 'idequipo')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
