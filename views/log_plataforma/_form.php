<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

<div class="log-plataforma-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idlog')->textInput() ?>

    <?= $form->field($model, 'idusuario')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'hora')->textInput() ?>

    <?= $form->field($model, 'modulo')->textInput() ?>

    <?= $form->field($model, 'accion')->textInput() ?>

    <?= $form->field($model, 'idregistro')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
