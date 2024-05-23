<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_ejidos;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;

use app\models\Sds_gis_capa_item;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_diagnostico */
?>


<div class="mds-r-diagnostico-view">

<?php $form = ActiveForm::begin(); ?>

<?php
       $una_dimension= Mds_r_variable_dimension::find()
        ->where(['idvardimension' => $idvardimension])        
        ->one();

?>
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
    <div class="row">
        <div class="col-md-12">   
            <?php  
                if ($una_dimension->origen==Mds_r_variable_dimension::ORIGEN_LOCALIDADES)
                {  // BUSCAR EL EJIDO
                    $un_ejido=Mds_r_ejidos::find()
                    ->where(['idejido' => $model->idejido])                        
                    ->one();   
                    $model->idejido=$un_ejido->ejido;

                
                    echo  $form->field($model, 'idejido')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Variable");

                    
                }
                else
                {
                    if ($una_dimension->origen==Mds_r_variable_dimension::ORIGEN_DISPOSITIVO)
                    {


                        $una_capa=Sds_gis_capa_item::find()
                        ->where(['idcapaitem' => $model->iddispositivo])                        
                        ->one();   
                        $model->iddispositivo=$una_capa->descripcion;
    
                    
                        echo  $form->field($model, 'iddispositivo')->textInput(['maxlength' => true,'readOnly' => true,
                                    'style' => 'background-color:#ffffff',])->label("Dispositivo");


                      
                    }
                }
            
            ?>        

        </div>  
        
    </div>  
    <div class="row">
        <div class="col-md-8">    
               
               <?php  
                    $una_dim=Sds_com_configuracion::find()
                    ->where(['idconfiguracion' => $model->valor_dimension])                        
                    ->one();   
                    $model->valor_dimension=$una_dim->descripcion;

                    echo  $form->field($model, 'valor_dimension')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Dimensión");
                ?>
               
        </div>  
        <div class="col-md-4">    
                <?= $form->field($model, 'valor')->textInput(['maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Valor") ?>
            
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

</div>
