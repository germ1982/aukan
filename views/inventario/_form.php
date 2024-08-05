<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventario-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-md-3"> 
            <?= $form->field($model,'idInventario')->textInput() ?>
        </div>           
        <div class="col-md-3">
            <?= $form->field($model, 'idarticulo')->textInput() ?>
        </div>     
    
        <div class="col-md-3">
            <?= $form->field($model, 'cantidad')->textInput() ?>
        </div>
            
        <div class="col-md-3">
            <?= $form->field($model, 'iddispositivo')->textInput() ?>
        </div>  

    </div>
    <div class="row">   

        <div class="col-md-4">
            <?= $form->field($model, 'idempleado')->textInput() ?>
        </div>
    
        <div class="col-md-4"> 
            <?= $form->field($model, 'idestado')->textInput() ?>
        </div>
                
        <div class="col-md-4">
            <?= $form->field($model, 'activo')->textInput() ?>
        </div>    
    </div>
    

    </div class="row">
        <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
    </div>

      
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
