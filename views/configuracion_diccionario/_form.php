<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConfiguracionDiccionario */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="configuracion-diccionario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'palabra_mal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'palabra_correcta')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
