<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Mds_rum_persona;
use app\models\Mds_rum_oferta_laboral;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_postulacion */
/* @var $form yii\widgets\ActiveForm */
?>
<?php  
$una_persona = Mds_rum_persona::findOne($model->id_persona);
$una_oferta = Mds_rum_oferta_laboral::findOne($model->id_oferta);
?>
<div class="mds-rum-postulacion-form"> view2.php

    <?php $form = ActiveForm::begin(); ?>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        PERSONA POSTULADA<br> 
        <div class="row">
                <div class="col-md-6">                                           
                        <?= $form->field($una_persona, 'nombres')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nombre') ?>
                </div>
                <div class="col-md-6">                                               
                        <?= $form->field($una_persona, 'apellido')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Apellido') ?>                     
                </div>       
        </div> 
        <div class="row">
                <div class="col-md-6">                                           
                        <?= $form->field($una_persona, 'dni')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('DNI') ?>
                </div>
                <div class="col-md-6">                                               
                        <?= $form->field($una_persona, 'email')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Email') ?>                     
                </div>       
        </div>
        <div class="row">
                <div class="col-md-6">                                           
                        <?= $form->field($una_persona, 'telfijo')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Telefono Fijo') ?>
                </div>
                <div class="col-md-6">                                               
                        <?= $form->field($una_persona, 'telcel')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Telefono Celular') ?>                     
                </div>       
        </div>
        
        <br>
        OFERTA LABORAL A LA QUE SE POSTULO
        <div class="row">
                <div class="col-md-12">
                    <?= $form->field($una_oferta, 'titulo')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Titulo') ?>
                </div>                                
        </div>
        <div class="row">
                <div class="col-md-6">
                    <?php  
                        $unafecha = explode ("-",$una_oferta->fecha_publicacion);
                        $fecha_publicacion= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);    
                        $una_oferta->fecha_publicacion=$fecha_publicacion;
                    ?>
                    <?= $form->field($una_oferta, 'fecha_publicacion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Fecha de Publicación') ?>
                </div>    
                <div class="col-md-6">                    
                    <?= $form->field($una_oferta, 'hora_publicacion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Hora de Publicación') ?>
                </div>                             
        </div>
        <div class="row">
            <div class="col-md-12">    
                <?= $form->field($una_oferta, 'descripcion')->textarea(['rows' => 6,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
            </div>
        </div>   
    </div>    

    <?php  //echo $form->field($model, 'fecha_post')->textInput(); ?>

    <?php //echo $form->field($model, 'hora_post')->textInput(); ?> 

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
