<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_cat_nov */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-cat-nov-form">

    <?php $form = ActiveForm::begin(); ?>
	<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">                         

    			<?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
			</div>
        </div>  
        <div class="row">  
            <div class="col-md-12">  
				<?= $form->field($model, 'activo')->checkBox(['selected' => $model->activo])?>     
    			
			</div>            
        </div>
    </div>    


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
