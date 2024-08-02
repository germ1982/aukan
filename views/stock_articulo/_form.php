<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StockArticulo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-articulo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class=" col-md-6">
            <?= $form->field($model, 'idarticulo')->textInput(['maxlength' => true]) ?>   
        </div>   
        <div class=" col-md-6">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>   
        </div>
    </div>

    

    <div class="row">
        <div class=" col-md-6">
            <?= $form->field($model, 'idtipo')->textInput() ?>  
        </div>
        <div class=" col-md-6">
            <?= $form->field($model, 'idmarca')->textInput() ?>   
        </div>            
            
    </div>

    

    <div class="row">
        <div class=" col-md-6">
            <?= $form->field($model, 'modelo')->textInput(['maxlength' => true]) ?>   
        </div>   
        <div class=" col-md-6">
            <?= $form->field($model, 'idrubro')->textInput() ?>    
        </div>    
    </div>
    
   

    <div class="row">
        <div class=" col-md-6">
            <?= $form->field($model, 'id_unidad_medida')->textInput() ?>     
        </div>
        <div class=" col-md-6">
            <?= $form->field($model, 'activo')->textInput() ?>   
        </div>
    </div>

   

    <div class="row">
        <div class=" col-md-6">
            <?= $form->field($model, 'imagen')->textInput(['maxlength' => true]) ?>
        </div>

    </div>    
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
