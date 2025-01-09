<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ViewStockArticulosCantidades */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="view-stock-articulos-cantidades-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idarticulo')->textInput() ?>

    <?= $form->field($model, 'rubro')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ingresado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'entregado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disponible')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
