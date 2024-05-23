<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_inv_entrega */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->title = 'Instancia';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mds-inv-entrega-form" >

    <?php $form = ActiveForm::begin(['id'=>"form_entrega"]); ?>      
          <?php
                $un_plantin = Sds_com_configuracion::find()->where(["idconfiguracion" => $model->idespecie])->one();  
             
                
                echo '
                <div class="row">
                    <div class="col-md-4">';
                        
                    echo $form->field($un_plantin, 'descripcion')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Especie");                   
                    echo '    
                    </div> 
                    <div class="col-md-3"> ';    
                        echo $form->field($model, 'cantidad')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Cantidad");        
                        echo '
                    </div> 
                    <div class="col-md-4">';

                    $fecha_entrega=$model->fecha;
                    $unafecha_div = explode (" ",$fecha_entrega);
                    $una_fecha_1=trim($unafecha_div[0]); 
                    $una_hora=trim($unafecha_div[1]); 

                    $unafecha = explode ("-",$una_fecha_1);
                    $lafecha= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);  

                    $model->fecha= $lafecha." ".$una_hora;
                    echo $form->field($model, 'fecha')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Fecha Entrega");        
                    echo '
                    </div>
                </div> ';

           
                
            ?>                      
        <br>

    
    </div>
    <br>

        <?php ActiveForm::end(); ?>    
</div>
<div class="row"  id="footer" >
                <div class="col-md-6">
                    <?php     
                        echo                
                        Html::button('Cerrar y Volver', [
                                                        
                            'class' => 'btn btn-primary btn-flat float-right',                                                                                                      
                            'id' => 'boton_cerrar',   
                            'data-dismiss' => "modal"                                                                      
                        ]);   
                    ?>
                </div>                
</div>
<?php
$this->registerJs(
    "    
    $('#boton_guardar2').click(function(){                
        guardar_plantin();
    });   
    $('#boton_guardar3').click(function(){                
        editar_plantin();
    });                  
    "
);
?>
<script>
function editar_plantin() {      
    especie=$("#especie").val(); 
    cantidad=$("#cantidad").val(); 
    identrega=$("#identrega").val();   
    $.post("index.php?r=mds_inv_persona/guardareditplantin&identrega="+identrega+"&especie="+especie+"&cantidad="+cantidad, function(data) {                       
            if(data=="exito") {      
                $("#form_entrega").html('Se guardaron exitosamente los cambios<br><br><br><br>');
                $("#boton_guardar3").hide();                                                      
            } else
            {                              
            }
        });    
}
function guardar_plantin() {      
    especie=$("#especie").val(); 
    cantidad=$("#cantidad").val(); 
    idpersona=$("#idpersona").val();     
    $.post("index.php?r=mds_inv_persona/guardarnuevoplantin&idpersona="+idpersona+"&especie="+especie+"&cantidad="+cantidad, function(data) {                       
            if(data=="exito") {      
                $("#form_entrega").html('Se guardo exitosamente la entrega del Plantin<br><br><br><br>');
                $("#boton_guardar2").hide();                                                      
            } else
            {                              
            }
        });    
}
</script>


