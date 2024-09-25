<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StockInformaticaIngreso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-informatica-ingreso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'idorigen')->textInput() ?>

    <?= $form->field($model, 'origen_referencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idempleado_recepcion')->textInput() ?>

    <?= $form->field($model, 'idusuario_carga')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
