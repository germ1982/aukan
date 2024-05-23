<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_planilla */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="mds-r-planilla-form2">

<?php $form = ActiveForm::begin(); ?>
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
<div class="row"> 
        
       
        <div class="col-md-6">
            
            <?= $form->field($model, 'idplantilla')
                    ->widget(Select2::classname(), 
                    [
                    'data' => ArrayHelper::map(
                        Sds_com_configuracion::find()
                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::R_TIPO_PLANTILLA, 'activo'=>'1'])
                        ->orderBy(['descripcion' => SORT_ASC])
                        ->all(),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccionar...', 
                    'disabled' => true,
                    'id' => 'cmb_idplantilla',
                    'onchange' =>   'traer_plantilla();',],

                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label('Plantilla seleccionada');
                ?>
            
        </div> 
        <div class="col-md-2">    
                <?= $form->field($model, 'mes')->textInput(['maxlength' => true])->label("Mes") ?>
        </div>  
        <div class="col-md-2">    
                <?= $form->field($model, 'anio')->textInput(['maxlength' => true])->label("Año") ?>
        </div>    
        <div class="col-md-2">    
                <?php echo $form->field($model, 'periodo')->        
                    dropDownList(['0' => 'Mensual', '1' => 'Trimestral','2' => 'Semestral','3' => 'Anual'])
                    ->label("Periodo");                                         
                    ?>  
        </div>                               
    </div>          
    <div class="row">
        <div class="col-md-12"> 
                
                
                <?= $form->field($model, 'idorganismo')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Sds_com_configuracion::find()
                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::R_ORGANISMO, 'activo'=>'1'])
                        ->orderBy(['descripcion' => SORT_ASC])
                        ->all(),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccione ...', 
                    'id' => 'cmb_idorganismo',
                    ],

                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label("Organismo");
                ?>
        </div> 

    </div>  
    
    <div class="row">   
        <div class="col-md-6" >         
            <?= $form->field($model, 'ver_diagnostico')->checkBox(['selected' => !$model->ver_diagnostico, 'id' => 'ver_diagnostico']) ?>           
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
                /*$.post("index.php?r=mds_r_plantilla/obtener_origen&id_plantilla="+id_plantilla, function(data) 
                {      
                        data = $.parseJSON(data); 
                        $("#modo_origen").html(data);                                 
                });*/
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
    function cambio_dimension(primera_vez = false) {
       
        id_plantilla=$("#cmb_idplantilla").val();
        id_dimension=$("#cmb_dimension").val();

        $.post("index.php?r=mds_r_plantilla/obtener_origen2&id_plantilla="+id_plantilla+"&id_dimension="+id_dimension, function(data) 
                {      
                        data = $.parseJSON(data); 
                        $("#modo_origen").html(data);                                 
                });       
       
   }
    
function cargarLocalidades() {
    $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {             
            $("select#cmb_localidad").html(data);
    });
}

JS;

$this->registerJs($script);

?>
