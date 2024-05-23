<?php

use yii\widgets\DetailView;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_oferta_laboral */
date_default_timezone_set('America/Argentina/Buenos_Aires');
?>



<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use app\models\Mds_rum_categoria;
//use app\models\Mds_rum_cualificacion;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_rum_empleador;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_oferta_laboral */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-rum-oferta-laboral-form"> 
    <?php $form = ActiveForm::begin(); 
    
        $fecha_fin_pub=$model->fecha_publicacionfin;        
        $hora_fin_pub=$model->hora_publicacionfin;            
        $fecha_actual= date('Y-m-d');
        $hora_actual=  date('H:i:s');  
        if (($fecha_actual>$fecha_fin_pub) || (($fecha_actual==$fecha_fin_pub) && ($hora_actual >= $hora_fin_pub)))
        {   $dateInput1 = explode('-',$fecha_fin_pub);
            $fecha_fin_pub2 = $dateInput1[2].'/'.$dateInput1[1].'/'.$dateInput1[0];        
            echo "<strong>LA OFERTA LABORAL FINALIZÓ EL ".$fecha_fin_pub2." a las ".$hora_fin_pub."</strong>";
        }       
        else
        {   }
    ?> 

    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">        
                    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12">    
                <?= $form->field($model, 'descripcion')->textarea(['rows' => 6,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12">    
            <?= $form->field($model, 'competencia')->textarea(['rows' => 6,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
            </div>
        </div>        
        <!--<div class="row">
            <div class="col-md-12">    -->
            <?php //echo $form->field($model, 'imagen')->textInput(['maxlength' => true]); ?>
            <!--</div>
        </div>-->
    </div> 
 
    <br>

        <div class="row">
            <div class="col-md-6" >
                CONTACTO 
                <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">                                                                                                                                                                                                                                  
                        <div class="row">
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'email1')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'email2')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'telefono1')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="col-md-12"> 
                                <?= $form->field($model, 'telefono2')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>
                            </div>
                        </div>
                </div>        
            </div>         
            
            <div class='col-md-6' align="center";>                                                                    
                        <?php
                        if ($model->imagen == null) {
                            echo ' No hay imagen guardada';
                        }
                        else
                        {
                            echo '
                            <figcaption class="text-center">IMAGEN PRINCIPAL</figcaption>
                                <img  width="100%"   src="';
                                //echo Html::img(Url::base()."/uploads/ofertas/".$model->imagen);
                                echo Url::base() . '/uploads/ofertas/'.$model->imagen ;
                                echo  '">
                                
                            ';

                        }
                            ?>                        
            </div>                                                       
        </div><br>

    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
                <div class="col-md-3">
                    <?php $ocupacion = Sds_com_configuracion::findOne($model->id_nivel_ocupacion);?>
                    <?= $form->field($ocupacion, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Nivel Ocupacion') ?>                    
                </div> 
                <div class="col-md-3">
                    <?php $nivel_exp = Sds_com_configuracion::findOne($model->id_experiencia);?>
                    <?= $form->field($nivel_exp, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Experiencia') ?>                    
                </div> 
                <div class="col-md-3">
                    <?php $categoria = Sds_com_configuracion::findOne($model->id_categoria);?>
                    <?= $form->field($categoria, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Categoria') ?>                    
                </div> 
                <div class="col-md-3">
                    <?php $cualificacion = Sds_com_configuracion::findOne($model->id_cualificacion);?>
                    <?= $form->field($cualificacion, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Cualificacion') ?>                    
                </div> 
        </div>  

        <div class="row">
                <div class="col-md-3">
                    <?php $tipotrabajo = Sds_com_configuracion::findOne($model->id_tipo_trabajo);?>
                    <?= $form->field($tipotrabajo, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Tipo Trabajo') ?>                    
                </div> 
                <div class="col-md-3">
                    <?php $durtrab = Sds_com_configuracion::findOne($model->id_dur_trabajo);?>
                    <?= $form->field($durtrab, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Dur. Trabajo') ?>                    
                </div>
                <div class="col-md-3">    
                    <?php 
                    //echo 'el id empleador es: '.$model->id_empleador;
                    $empleador = Mds_rum_empleador::findOne($model->id_empleador);?>            
                    <?php 
                        if ($empleador == null)
                        {   $model->mensaje='Sin asignar';
                            echo $form->field($model, 'mensaje')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Empresa'); 
                        }
                        else
                        {
                            echo $form->field($empleador, 'nombre')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Empresa') ;
                        }                        
                        ?>                   
                </div>
        </div>
    </div><br>     
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12"> 
                <?= $form->field($model, 'ubicacion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ->label('Domicilio'); ?>                                   
            </div>
        </div>    
        <div class="row">
            <div class="col-md-6"> 
            <?php
                    if ($model->id_localidad ==''){}
                    else
                    {
                        //echo 'el id localidad es: '.$model->id_localidad;
                        $para_id_una_prov=Sds_com_localidad::find() 
                           ->select(['idprovincia'])                                   
                           ->where(['idlocalidad' => $model->id_localidad])
                           ->one();  
                        $una_provincia=Sds_com_provincia::find() 
                           ->select(['descripcion'])                                   
                           ->where(['idprovincia' => $para_id_una_prov->idprovincia])
                           ->one();  
                           $model->la_provincia=$una_provincia->descripcion;
                           echo $form->field($model, 'la_provincia')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Provincia');
                    }
                ?>
            </div>    
            <div class="col-md-6"> 
                    <?php
                    if ($model->id_localidad ==''){}
                    else
                    {
                        $loc = Sds_com_localidad::findOne($model->id_localidad);
                         echo $form->field($loc, 'descripcion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Localidad');
                    }
                    ?>                    
            </div>
        </div>
    </div>
    <br>
    DURACION DE LA PUBLICACION
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-3">  
                <?php                   
                        $unafecha1 = explode ("-",$model->fecha_publicacion);
                        $fecha_publicacion= trim($unafecha1[2])."/".trim($unafecha1[1])."/".trim($unafecha1[0]);    
                        $model->fecha_publicacion2=$fecha_publicacion;                
                ?>               
                <?= $form->field($model, 'fecha_publicacion2')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                   
            </div>
            <div class="col-md-3"> 
                <?= $form->field($model, 'hora_publicacion')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                                   
            </div>
            <div class="col-md-3"> 
                <?php                       
                    $unafecha2 = explode ("-",$model->fecha_publicacionfin);
                    $fecha_publicacionfin= trim($unafecha2[2])."/".trim($unafecha2[1])."/".trim($unafecha2[0]);    
                    $model->fecha_publicacionfin2=$fecha_publicacionfin;        
                ?>   
                <?= $form->field($model, 'fecha_publicacionfin2')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                                                   
            </div>
            <div class="col-md-3">                 
                <?= $form->field($model, 'hora_publicacionfin')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                                                   
            </div>
        </div>
    </div> <br>                        
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-4">                            
                <?= $form->field($model, 'salario')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                                                   
            </div>
            <div class="col-md-4"> 
                <?php
                    if ($model->genero == 'F') {$model->genero = 'Femenino';}
                    else
                    {
                        if ($model->genero == 'M') {$model->genero = 'Masculino';}
                        else
                        {
                            if ($model->genero == 'I') {$model->genero = 'Otros';}
                        }
                    }                    
                ?>
                <?= $form->field($model, 'genero')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>                                
            </div>        
        </div>
        <div class="row">
            <div class="col-md-4">    
                <?php
                    if ($model->activo==1) {echo '*** La oferta Laboral esta activa';}
                    else
                    if ($model->activo==0) {echo '*** La oferta Laboral no esta activa';}
                ?>                
            </div>
        </div>
    </div>   
    
    
    

</div>   <br>


<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">        
        <div class="row" >
            <div class="col-md-12">    
                <?php
                    if ($model->ver_info_empresa==1) {echo '*** Publicación anónima ***';}
                    else
                    if (($model->ver_info_empresa==0) || ($model->ver_info_empresa==null)) {echo '*** Publicación no anónima ***';}
                ?>                    
            </div>                            
        </div>
        <div class="row" style="display: <?php  if ($model->ver_info_empresa==1){echo 'block';}else {echo 'none';} ?>;" id="div_info_empresa">
            <div class="col-md-12">    
            <?= $form->field($model, 'info_empresa')->textarea(['rows' => 6,"readOnly"=>true,'style'=>'background-color:#ffffff']) ?>  
            
            </div>                            
        </div>





    <?php  //echo $form->field($model, 'fechaalta')->textInput(); ?>
    <?php //echo $form->field($model, 'horaalta')->textInput(); ?>    
    <?php //echo $form->field($model, 'num_visto')->textInput() ?>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>


