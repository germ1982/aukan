<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_observacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-observacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>   
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
