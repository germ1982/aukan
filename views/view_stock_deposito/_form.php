<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\View_stock_deposito */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="view-stock-deposito-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idarticulo')->textInput() ?>

    <?= $form->field($model, 'deposito')->textInput() ?>

    <?= $form->field($model, 'organismo')->textInput() ?>

    <?= $form->field($model, 'deposito_descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stock')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
