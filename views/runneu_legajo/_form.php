<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RunneuLegajo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="runneu-legajo-form">

    <?php $form = ActiveForm::begin(); ([
        'options' => ['enctype' => 'multipart/form-data']
    ]);?>
    

    <?= $form->field($model, 'num_legajo')->textInput() ?>

    <?= $form->field($model, 'dni')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'archivo_adjunto')->fileInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
