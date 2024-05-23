<?php

use yii\widgets\DetailView;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_r_variable_dimension;
use app\models\Sds_gis_capa;
use app\models\Mds_r_plantilla;
use app\models\Mds_r_planilla;


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_planilla */
?>

<div class="mds-r-planilla-form2">

<?php $form = ActiveForm::begin(); ?>
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
    <div class="row">
        <div class="col-md-2">    
                <?= $form->field($model, 'mes')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Mes") ?>
        </div>  
        <div class="col-md-2">    
                <?= $form->field($model, 'anio')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Año") ?>
        </div>    
        <div class="col-md-4">    
                <?php
                       if ($model->periodo==0){
                        $model->periodo="Mensual";
                       }else
                       {
                        if ($model->periodo==1)
                        {
                            $model->periodo="Trimestral";
                        }
                        else
                        {
                            if ($model->periodo==2)
                            {
                                $model->periodo="Semestral";
                            }
                            else
                            {
                                if ($model->periodo==3)
                                {
                                    $model->periodo="Anual";
                                }
                                else
                                {
                                    $model->periodo="Desconocido";
                                }
                                
                            }
                            
                        }
                       }                       
                ?>
                 <?= $form->field($model, 'periodo')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Periodo") ?> 
                
        </div>                
    </div>    
    <div class="row">  
        <div class="col-md-6">    
                <?php
                        $una_conf=Sds_com_configuracion::find()
                        ->where(['idconfiguracion' => $model->idorganismo])                        
                        ->one();
                        $model->idorganismo=   $una_conf->  descripcion; 
                ?>
                 <?= $form->field($model, 'idorganismo')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Organismo") ?>                   
        </div>               
        <div class="col-md-6">          
             <?php
                        $una_conf=Sds_com_configuracion::find()
                        ->where(['idconfiguracion' => $model->idplantilla])                        
                        ->one();
                        $model->idplantilla=   $una_conf->  descripcion; 
                ?>
                 <?= $form->field($model, 'idplantilla')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Plantilla") ?>                                                           
        </div>  
        
                          
    </div>  
    <div class="row">

        <div class="col-md-6">
            <?php
                   echo $model->ver_diagnostico==0 ? "<b>Ver planilla en diagnóstico</b>" : "<b>No ver planilla en diagnóstico</b>"
            ?>
            
        </div>    
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


<?php
$script = <<<  JS

    function traer_plantilla(primera_vez = false) {
        id_plantilla=$("#cmb_idplantilla").val();// devuelve idtipoplantilla, que es de configuracion tipo
        
          
                $.post("index.php?r=sds_com_configuracion/cmb_idvariable&id_plantilla="+id_plantilla, function(data) {                                 
                        
                           $("select#cmb_idvariable").html(data);
                });
                $.post("index.php?r=mds_r_plantilla/obtener_origen&id_plantilla="+id_plantilla, function(data) 
                {      
                        data = $.parseJSON(data); 
                        $("#modo_origen").html(data);                                 
                });
                $("select#cmb_dimension").html("<option value='null'>Seleccione Variable</option>");
                
                $.post("index.php?r=mds_r_plantilla/obtener_plantilla&id_plantilla="+id_plantilla, function(data) 
                {      
                        data = $.parseJSON(data); 

                        $("#origen").val(data[0]['origen']);  
                        $("#id_giscapa").val(data[0]['id_gis_capa']);
                                                 
                });
    }

    

    function traer_dimension(primera_vez = false) {
       
        idvariable=$("#cmb_idvariable").val();// devuelve idtipoplantilla, que es de configuracion tipo
        if (idvariable=="null")
        {
            $("select#cmb_dimension").html("");
            
        }else
        {  
            $.post("index.php?r=sds_com_configuracion/cmb_iddimension&idvariable="+idvariable, function(data) {                                 
                        
                        $("select#cmb_dimension").html(data);
             });
        }
    }
function cargarLocalidades() {
    $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {             
            $("select#cmb_localidad").html(data);
    });
}

JS;

$this->registerJs($script);

?>
