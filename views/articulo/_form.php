<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Articulo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="articulo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class=" col-md-4">
            <?= $form->field($model, 'idarticulo')->textInput() ?>   
        </div>   
        <div class=" col-md-4">
            <?= $form->field($model, 'descripcion')->textInput() ?>   
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'idtipo')->textInput() ?>  
        </div>
    </div>

    

    <div class="row">    
        <div class=" col-md-4">
            <?= $form->field($model, 'idmarca')->textInput() ?>   
        </div>        
        <div class=" col-md-4">
            <?= $form->field($model, 'modelo')->textInput() ?>   
        </div>           
        <div class=" col-md-4">
            <?= $form->field($model, 'idrubro')->textInput() ?>    
        </div>    
    </div>
    
   

    <div class="row">
        <div class=" col-md-4">
            <?= $form->field($model, 'id_unidad_medida')->textInput() ?>     
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'activo')->textInput() ?>   
        </div>    
        <div class=" col-md-4">
            <?= $form->field($model, 'imagen')->textInput() ?>
        </div>

    </div>    
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
