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
/* @var $model app\models\Mds_r_variable_dimension */
?>



<div class="mds-r-variable-dimension-form">
<?php $form = ActiveForm::begin(); ?>
<?php
$idplanilla=$model->idplanilla;
$unaplanilla=Mds_r_planilla::find()
->where(['idplanilla' => $model->idplanilla])                        
->one();
$model->idplanilla=$idplanilla; 
$var_idvariable=0;

?>
<?=  $form->field($model, 'idvariable')->hiddenInput(['id'=>'elidvariable'])->label(false); ?>
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  
    
    <div class="row">                
        <div class="col-md-6">  
            <?php    
                         //echo $model->idvardimension.' '.$unaplanilla->idplantilla.' '.$model->origen.'<br>';
                        $model->id_plantilla=$unaplanilla->idplantilla;  
                                
                        $una_plantilla = Mds_r_plantilla::find()
                        ->where(['idtipoplantilla' => $unaplanilla->idplantilla])
                        ->one();

                        //$model->origen=$una_plantilla->origen; 
                        $model->id_giscapa=$una_plantilla->id_gis_capa;
                        /*$unadimension=Mds_r_variable_dimension::find()
                        ->where(['idplanilla' => $idplanilla])                        
                        ->one();*/
                        //echo $model->idvardimension.' '.$unaplanilla->idplantilla.' '.$model->origen.'<br>';
            ?>
             <?php
                        $una_conf=Sds_com_configuracion::find()
                        ->where(['idconfiguracion' => $model->id_plantilla])                        
                        ->one();                                               
                ?>   

                <?php

                    $una_variable=Sds_com_configuracion::find()
                    ->where(['idconfiguracion' => $model->idvariable])                        
                    ->one();   
                    //echo $model->idvariable;
                    $var_idvariable=$model->idvariable;
                    
                    $model->idvariable=$una_variable->descripcion;

                ?>
                <?= $form->field($model, 'idvariable')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Variable") ?>   
                
        </div>  
        <div class="col-md-6">   
        <?php
  
            $una_dimension=Sds_com_configuracion_tipo::find()
            ->where(['idconfiguraciontipo' => $model->iddimension])                        
            ->one();   

            
            
            $model->iddimension=$una_dimension->descripcion;
            ?>
            <?= $form->field($model, 'iddimension')->textInput(['maxlength' => true,'readOnly' => true,
                        'style' => 'background-color:#ffffff',])->label("Dimensión") ?>  
           
        </div>                   
    </div>   
    <div class="row">   
        <div class="col-md-12" >
                    <?= $form->field($model, 'detalle')->textarea(['rows' => 4, 'readonly'=>true,'style' => 'background-color:#ffffff']) ?>                   
        </div>       
    </div>      
    <div class="row">   
        <div class="col-md-12" >
                    <?= $form->field($model, 'observacion')->textarea(['rows' => 4, 'readonly'=>true,'style' => 'background-color:#ffffff']) ?>
        </div>
    </div>    
    
    <div class="row">   
        <div class="col-md-12" >
                <?php 
                if ($model->mapear) 
                {
                    echo '<b>Mapear</b>';
        
                } 
                else {echo '<b>No Mapear</b>';} 
                ?>

<?php 
                if ($model->mapear) 
                {

                    $un_mapa=Sds_com_configuracion::find()
                    ->where(['idconfiguracion' => $model->tipomapa])                        
                    ->one();   
                    $model->tipomapa=$un_mapa->descripcion;
                    echo $form->field($model, 'tipomapa')->textInput(['maxlength' => true,'readOnly' => true,
                    'style' => 'background-color:#ffffff',])->label("Tipo Mapa");

                } 
            
                ?>
            
        </div>          

            <?= $form->field($model, 'origen')->hiddenInput(['id'=>'origen'])->label(false); ?>
            <?= $form->field($model, 'id_giscapa')->hiddenInput(['id'=>'id_giscapa'])->label(false); ?>
            
            <?= $form->field($model, 'id_plantilla')->hiddenInput(['id'=>'id_plantilla'])->label(false); ?>
            <?= $form->field($model, 'idvardimension')->hiddenInput(['id'=>'idvardimension'])->label(false); ?>
            <?=  $form->field($model, 'iddimension')->hiddenInput(['id'=>'el_id_dimension'])->label(false); ?>
            
                
    </div>
             
    


            <div class="row">            
                <div class="col-md-5" style="display:none" id="div_tipomapa">   
                    
            
                </div>                            
            </div> 
            
            <div class="row">
                <div class="col-md-12"  id="modo_origen">  
                <div class="p-3 mb-2 bg-secondary text-white">
                    <?php     
                    /*           
                        $cad_return="";
                        if ($unadimension->origen== Mds_r_planilla::ORIGEN_DISPOSITIVO)  
                        {
                                //$una_plantilla = Mds_r_plantilla::find()->where(['idtipoplantilla' => $model->origen])->asArray()->one();
                                //$gis_capa = Sds_gis_capa::find()->where(['idcapa' => $unadimension->origen])->asArray()->one();
                                $cad_return="<b>Origen:</b> Dispositivo -> ";//.$gis_capa['descripcion'];
                                
                            
                        }    
                        else
                        {  $cad_return="<b>Origen:</b> Localidades.";
                        }  
                        echo $cad_return;   
                        */
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
    
    $('#mapear').on('change', function() {
        if ($(this).is(':checked') ) {
            $('#div_tipomapa').show();
            //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") => Seleccionado");
            
        } else {
            //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") => Deseleccionado");
            $('#div_tipomapa').hide();
        }
    });

    $(document).ready(traer_plantilla);  

    function traer_plantilla(primera_vez = false) {
               
        id_plantilla=$("#id_plantilla").val();// devuelve idtipoplantilla, que es de configuracion tipo
        id_dimension=$("#el_id_dimension").val();
        idvariable=$("#elidvariable").val();
        origen=$("#origen").val();
        idvardimension=$("#idvardimension").val();
        
                $.post("index.php?r=sds_com_configuracion/cmb_idvariable&id_plantilla="+id_plantilla, function(data) {                                                           
                           $("select#cmb_idvariable").html(data);
                });
                
                //$.post("index.php?r=mds_r_plantilla/obtener_origencompleto&id_plantilla="+id_plantilla+"&variable_diag="+idvariable+"&iddimension="+id_dimension, function(data)
                $.post("index.php?r=mds_r_plantilla/obtener_origen4&idvardim="+idvardimension+"&id_plantilla="+id_plantilla+"&id_dimension="+id_dimension+"&idorigen="+origen, function(data) 
                {      
                        data = $.parseJSON(data); 
                        $("#modo_origen").html('<p class="p-2 bg-light   text-white">'+data+'</p>');                                 
                });
               
                $("select#cmb_dimension").html("<option value='null'>Seleccione Variable</option>");
                
                $.post("index.php?r=mds_r_plantilla/obtener_plantilla&id_plantilla="+id_plantilla, function(data) 
                {      
                        data = $.parseJSON(data); 

                       //$("#origen").val(data[0]['origen']);  
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


