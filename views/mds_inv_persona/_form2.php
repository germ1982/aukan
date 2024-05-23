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
          
            if ($accion=='crear')
            {   $model->idpersona=$idpersona;
                echo $form->field($model, 'idpersona')->hiddenInput(["id" => "idpersona"]) ->label(false);
                echo '
                <div class="row">
                    <div class="col-md-4">';
                        
                        echo $form->field($model, 'idespecie')->dropDownList(
                            ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(91), 'idconfiguracion', 'descripcion'),
                            //['prompt' => 'Seleccionar Sexo ...', 'disabled' => true]
                            ['prompt' => 'Sel. Especie ...', 'disabled' => false,'id' => 'especie'],
                            
                        );                       
                    echo '    
                    </div> 
                    <div class="col-md-3"> ';              
                        echo $form->field($model, 'cantidad')->textInput(["id" => "cantidad"])->label("Cantidad");
                        echo '
                    </div>            
                </div> ';
            }
            else
            {   
                echo $form->field($model, 'identrega')->hiddenInput(["id" => "identrega"]) ->label(false);
                echo '
                <div class="row">
                    <div class="col-md-4">';
                        
                        echo $form->field($model, 'idespecie')->dropDownList(
                            ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(91), 'idconfiguracion', 'descripcion'),
                            //['prompt' => 'Seleccionar Sexo ...', 'disabled' => true]
                            ['prompt' => 'Sel. Especie ...', 'disabled' => false,'id' => 'especie'],
                            
                        );                       
                    echo '    
                    </div> 
                    <div class="col-md-3"> ';              
                        echo $form->field($model, 'cantidad')->textInput(["id" => "cantidad"])->label("Cantidad");
                        echo '
                    </div>            
                </div> ';

            }
                
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
                                                        
                            'class' => 'btn btn-success btn-flat float-right',                                                                                                      
                            'id' => 'boton_cerrar25',   
                            'data-dismiss' => "modal"    
                            //'onclick'=>"$('#modal').modal('hide');"                                                                  
                        ]);   
                    ?>
                </div>
                <?php
                    if ($accion=='crear')
                    {   echo '
                        <div class="col-md-6">';                           
                            echo 
                            Html::button('Guardar Registro', [                                    
                            'class' => 'btn btn-primary btn-flat',                                                                                                      
                            'id' => 'boton_guardar2', 
                            'style' => 'display: block;',    
                            ]);                                
                        echo ' 
                    </div>';
                    }
                    else
                    {
                        echo '
                        <div class="col-md-6">';                           
                            echo 
                            Html::button('Guardar Registro', [                                    
                            'class' => 'btn btn-primary btn-flat',                                                                                                      
                            'id' => 'boton_guardar3', 
                            'style' => 'display: block;',    
                            ]);                                
                        echo ' 
                    </div>';

                    }
                    
                ?>
                

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

    $('#cerrarme').click(function(e){  
        
        e.preventDefault();
        $('#ajaxCrudModal').modal('hide'); 
        return false;
    }); 


            
    "
);
?>
<script>
    function vamosacerrar() {   
        alert("aca");
        $('#ajaxCrudModal').modal('hide');  
        
    }
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
function guardar_plantin() {      alert('guardar el plantin');
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


