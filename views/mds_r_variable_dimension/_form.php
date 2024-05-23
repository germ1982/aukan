<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_planilla;
use app\models\Mds_r_plantilla;



/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_variable_dimension */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="mds-r-variable-dimension-form">
<?php $form = ActiveForm::begin(); ?>
<?php
$unaplanilla=Mds_r_planilla::find()
->where(['idplanilla' => $idplanilla])                        
->one();
$model->idplanilla=$idplanilla; 
?>
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">  
    
    <div class="row">                
        <div class="col-md-6">  
            <?php       
                        $model->id_plantilla=$unaplanilla->idplantilla;  
                        
        
                        $una_plantilla = Mds_r_plantilla::find()
                        ->where(['idtipoplantilla' => $unaplanilla->idplantilla])
                        ->one();

                        $model->origen=$una_plantilla->origen; 
                        $model->id_giscapa=$una_plantilla->id_gis_capa;
                        /*$unadimension=Mds_r_variable_dimension::find()
                        ->where(['idplanilla' => $idplanilla])                        
                        ->one();*/

            ?>
             <?php
                        $una_conf=Sds_com_configuracion::find()
                        ->where(['idconfiguracion' => $model->id_plantilla])                        
                        ->one();                                               
                ?>   
                <?= $form->field($model, 'idvariable')->widget(Select2::classname(), [
                    
                        'options' => ['placeholder' => 'Seleccione ...', 
                        'id' => 'cmb_idvariable',
                        'onchange' =>   'traer_dimension();',
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Variable");
                ?>                  
        </div>  
        <div class="col-md-6">   
            <?= $form->field($model, 'iddimension')->widget(Select2::classname(), [
                    
                    'options' => ['placeholder' => 'Seleccione ...', 
                    'id' => 'cmb_dimension',
                    'onchange' =>   'traer_origen("'.$model->id_plantilla.'");',
                    ],

                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label("Dimensión");
            ?>               
        
        
        <?php
            
           /* $una_conf=Sds_com_configuracion::find()
                        ->where(['idconfiguracion' => $unadimension->idvariable])                        
                        ->one();

            $model->idvariable=   $una_conf->  descripcion; 

            $una_conf=Sds_com_configuracion_tipo::find()
            ->where(['idconfiguraciontipo' => $unadimension->iddimension])                        
            ->one();

            $model->iddimension=   $una_conf->  descripcion;     
            
            $una_conf=Sds_com_configuracion::find()
            ->where(['idconfiguracion' => $unadimension->tipomapa])                        
            ->one();

            $model->tipomapa=   $una_conf->  descripcion;  */     
            ?>    
                                
               
           
        </div>                   
    </div>   
    <div class="row">   
        <div class="col-md-12" >
            <?= $form->field($model, 'detalle')->textarea(['rows' => 4]) ?>
        </div>       
    </div>      
    <div class="row">   
        <div class="col-md-12" >
            <?= $form->field($model, 'observacion')->textarea(['rows' => 4]) ?>
        </div>
    </div>    
    <div class="row">   
        <div class="col-md-3" >
                <?php 
                /*if ($model->mapear) 
                {
                    echo '<b>Mapear</b>';
                } 
                else {echo '<b>No Mapear</b>';} */
                ?>
            
        </div>
                      
            <?= $form->field($model, 'origen')->hiddenInput(['id'=>'origen'])->label(false); ?>
            <?= $form->field($model, 'id_giscapa')->hiddenInput(['id'=>'id_giscapa'])->label(false); ?>
            
            <?= $form->field($model, 'id_plantilla')->hiddenInput(['id'=>'id_plantilla'])->label(false); ?>
                
    </div>
             
    


            <div class="row">         
                <div class="col-md-2" style="padding-top:30px;">                
                    <?= $form->field($model, 'mapear')->checkbox(array('id'=>'mapear')) ?>                
                </div>            
                <div class="col-md-5" style="display:none" id="div_tipomapa">   
                    
                    <?= $form->field($model, 'tipomapa')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::DIAGNOSTICO_TIPO_MAPA, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                            
                            'options' => ['placeholder' => 'Seleccione ...', 
                            'id' => 'cmb_tipomapa',
                            
                            ],

                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])
                        ->label("Tipo Mapa");
                    ?>             
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
        
                $.post("index.php?r=sds_com_configuracion/cmb_idvariable&id_plantilla="+id_plantilla, function(data) {                                                           
                           $("select#cmb_idvariable").html(data);
                });
                $.post("index.php?r=mds_r_plantilla/obtener_origen&id_plantilla="+id_plantilla, function(data) 
                {      
                        data = $.parseJSON(data); 
                        //$("#modo_origen").html('<p class="p-2 bg-light   text-white">'+data+'</p>');                                 
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

    function traer_origen(id_plantilla ) {
       
       idvariable=$("#cmb_idvariable").val();// devuelve idtipoplantilla, que es de configuracion tipo
       if (idvariable=="null")
       {                     
       }else
       {    
            id_dimension=$("#cmb_dimension").val();
            if (id_dimension=="null"){                
            }
            else
            {                
                $.post("index.php?r=mds_r_plantilla/obtener_origencompleto&id_plantilla="+id_plantilla+"&variable_diag="+idvariable+"&iddimension="+id_dimension, function(data)
                {      
                        data = $.parseJSON(data);                         
                        $("#modo_origen").html('<p class="p-2 bg-light   text-white">'+data+'</p>');                                 
                        $.post("index.php?r=mds_r_plantilla/obtener_plantillasxdiagxdim&id_plantilla="+id_plantilla+"&variable_diag="+idvariable+"&iddimension="+id_dimension, function(data) 
                        {      
                            data = $.parseJSON(data); 
                            $("#origen").val(data[0]['origen']);                                                              
                        });
                });
            }                  
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

