<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_persona;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_novedad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-novedad-form">

    <?php $form = ActiveForm::begin(); ?> 
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">                    
                
                <?= $form->field($model, 'titulo')->textarea(['rows' => 2,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">                    
                
                <?= $form->field($model, 'contenido')->textarea(['rows' => 6,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">   
                <?php
                    if ($model->activo==0){  $model->auxiliar='no activo'; }
                    else {$model->auxiliar='activo';   }
                ?>
                
                <?= $form->field($model, 'auxiliar')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Activo') ?>
            </div>
            <div class="col-md-4">    
                <?php
                    if ($model->publicado==0){  $model->auxiliar='no publicado'; }
                    else {$model->auxiliar='publicado';   }
                ?>
                <?= $form->field($model, 'auxiliar')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Publicado') ?>
            </div>
        </div>
        <div class="row">
        <div class='col-md-6' align="center";>            
                                                           

                                                           <?php
                                                           if ($model->imagen == null) {
                                                            echo 'No tiene imagen guardada.<br>Recomendamos registrar una imagen';
                                                           }
                                                           else
                                                           {
                                                               echo '
                                                               <figcaption class="text-center">IMAGEN PRINCIPAL</figcaption>
                                                                   <img  width="100%"   src="';
                                                                   
                                                                   echo Url::base() . '/uploads/institucional/'.$model->imagen ;
                                                                   echo  '">
                                                                   
                                                               ';
                                   
                                                           }
                                                               ?>
                                                           
                                               </div>       
           
        </div>
        
        
    </div>


    <?php //echo  $form->field($model, 'comment_status')->textInput(['maxlength' => true]); ?>

    <?php  // echo $form->field($model, 'comment_count')->textInput(); ?>
  
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
