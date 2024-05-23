<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
	
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Mds_rum_persona;
use app\models\Mds_rum_oferta_laboral;
use app\models\Mds_seg_usuario;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_postulacion */
/* @var $form yii\widgets\ActiveForm */
?>
<?php  
//$una_persona = Mds_rum_persona::findOne($model->id_persona);
$fecha_postulacion=$model->fecha_post;
$unafecha = explode ("-",$fecha_postulacion);
$fecha_postulacion= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);  
$hora_postulacion=$model->hora_post;
$estado=$model->estado;

$una_oferta = Mds_rum_oferta_laboral::findOne($model->id_oferta);
$una_persona=Mds_rum_persona::find()
->select ('mds_rum_persona.id as id, mds_rum_persona.email as email, mds_rum_persona.telfijo as telfijo, 
mds_rum_persona.telcel as telcel, sds_com_persona.nombre as nombres, sds_com_persona.apellido as apellido,
sds_com_persona.documento as dni,mds_rum_persona.id_seg_usuario as id_seg_usuario')
->where(["id" => $model->id_persona])
->innerJoin ('sds_com_persona', 'sds_com_persona.idpersona = mds_rum_persona.id_com_persona') 
->one();
$un_seg_usuario = Mds_seg_usuario::findOne($una_persona->id_seg_usuario);

?>
<div class="mds-rum-postulacion-form">

    <?php $form = ActiveForm::begin(); ?>
        
    <div class="row">
        <div class="col-md-6">  
                <div class="row">
                        <div class="col-md-12">                                           
                        <?php echo "<strong>SE POSTULÓ EL ".$fecha_postulacion." A LAS ".$hora_postulacion."</strong>";   ?>
                        </div>  
                </div>
                <div class="row">
                        <div class="col-md-12"> 
                        <?php
                                $cad_estado_post="";
                                switch ($estado) {
                                        case "descartado":
                                                $cad_estado_post="DESCARTADO";
                                            break;
                                        case "postulado":
                                                $cad_estado_post="POSTULADO";
                                            break;
                                        case "elegido":
                                                $cad_estado_post="ELEGIDO";
                                        break;
                                        case "seleccionado":
                                                $cad_estado_post="SELECCIONADO";
                                            break;
                                        case "finalista":
                                                $cad_estado_post="FINALISTA";
                                        break;
                                    }
                                    echo "<p>Estado de la Postulación: <strong>".$cad_estado_post."</strong></p>";
                        ?>
                                             
                        </div>  
                </div>
                
        </div>
        <div class="col-md-6">
                <?= Html::submitButton('Notificar Estado', ['id'=>'boton_not','class' => 'submit','submit'=>array('NotificarEstado') ]) ?>
        </div>                         
     </div> 
  
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
                        <?= $form->field($un_seg_usuario, 'mail')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff'])->label('Email') ?>                     
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
        <?php
                     
            $fecha_fin_pub=$una_oferta->fecha_publicacionfin;        
            $hora_fin_pub=$una_oferta->hora_publicacionfin;  
            $fecha_publicacion=$una_oferta->fecha_publicacion;        
            $hora_publicacion=$una_oferta->hora_publicacion;           
            $fecha_actual= date('Y-m-d');
            $hora_actual=  date('H:i:s'); 
           
            $activa=$una_oferta->activo == 1 ? 'Activa' : 'No Activa';
            $cad=$activa; 
            if (($fecha_actual>$fecha_fin_pub) || (($fecha_actual==$fecha_fin_pub) && ($hora_actual >= $hora_fin_pub)))
            {
                $cad.=" | Finalizada";
            }   
            else
            {
                $cad.=" | No Finalizada";     
            }  
            if (($fecha_actual>$fecha_publicacion) || (($fecha_actual==$fecha_publicacion) && ($hora_actual >= $hora_publicacion)))
            {
                $cad.=" | Publicada";
            }
            else
            {
                $cad.=" | No Publicada";    
            }  
            
        ?>
        <div class="row">
                <div class="col-md-12">                                           
                        <?php echo "<strong>ESTADO DE LA OFERTA: ".$cad."</strong>";   ?>
                </div>  
        </div>
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
<?php
// javascript for triggering the dialogs
$cadena_not='"index.php?r=mds_rum_postulacion/notificar_estado&id='.$model->id.'"';
$js = <<< JS
    $("#boton_not").on("click", 
        function() 
        {
                krajeeDialog.confirm("¿Seguro desea enviar notificación?", 
                function (result) 
                {
                        if (result) 
                        {
                                // Aqui debe ir el codigo que llama a la accion del controlador                                
                                $.post($cadena_not,function(data) 
                                {krajeeDialog.alert("Se ha enviado la notificación con exito.");});
                        } 
                });
                return false;
        });
JS;
// register your javascript
$this->registerJs($js);
?>