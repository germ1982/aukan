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
<div class="mds-inv-entrega-form"  id="form_entrega">
<b>Seguro desea eliminar la entrega del plantin?</b><br><br><br>
   
</div>
<div class="row"  id="footer" >
                <div class="col-md-4">               
                 <input type="hidden" id="id_plantin" name="id_plantin" value="<?= $id?>">
                    <?php     
                        echo                
                        Html::button('Cancelar', [
                                                        
                            'class' => 'btn btn-success btn-flat float-right',                                                                                                      
                            'id' => 'boton_cerrar8',   
                            'data-dismiss' => "modal"                                                                      
                        ]);   
                    ?>
                </div>
                <div class="col-md-4">                                
                    <?php     
                        echo                
                        Html::button('Cerrar', [
                                                        
                            'class' => 'btn btn-success btn-flat float-right',                                                                                                      
                            'id' => 'boton_cerrar5',   
                            'style' => 'display: none;',  
                            'data-dismiss' => "modal"                                                                      
                        ]);   
                    ?>
                </div>
                <div class="col-md-4">
                    <?php     
                        echo                
                        Html::button('Aceptar', [
                                                        
                            'class' => 'btn btn-primary btn-flat float-right',                                                                                                      
                            'id' => 'boton_aceptar',                                                                                            
                        ]);   
                    ?>
                </div>
                
                               

</div>
<?php
$this->registerJs(
    "    
    $('#boton_aceptar').click(function(){                
        eliminar_plantin();
    });                         
    "
);
?>
<script>

function eliminar_plantin() {      
    id_plantin=$("#id_plantin").val(); 
    $.post("index.php?r=mds_inv_persona/borrarplantin&id_plantin="+id_plantin, function(data) {                       
            if(data=="exito") {      
                $("#form_entrega").html('Se elimino exitosamente la entrega del Plantin<br><br><br>');
                $("#boton_aceptar").hide(); 
                $("#boton_cerrar8").hide(); 
                $("#boton_cerrar5").show(); 
                                                                     
            } else
            {                              
            }
        });    
}
</script>


