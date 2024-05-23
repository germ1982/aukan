<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_gis_capa_item;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_inv_entrega */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->title = 'Instancia';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="mds-inv-entrega-view" >

    <?php $form = ActiveForm::begin(['id'=>"form_entrega"]); ?>      
          <?php
                $un_plantin = Sds_com_configuracion::find()->where(["idconfiguracion" => $model->idespecie])->one();  
             
                
                echo '
                <div class="row">
                    <div class="col-md-4">';
                        
                    echo $form->field($un_plantin, 'descripcion')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Especie");                   
                    echo '    
                    </div> 
                    <div class="col-md-2"> ';    
                        echo $form->field($model, 'cantidad')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Cantidad");        
                        echo '
                    </div>  
                    
                    <div class="col-md-4"> ';  
                        
                        $un_lugar_ent = Sds_gis_capa_item::findOne($model->idlugar);
                        $model->lugardeentrega=$un_lugar_ent->descripcion;                        
                        echo $form->field($model, 'lugardeentrega')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Lugar de Entrega");        
                        echo '
                    </div> 
                </div> 
                
                <div class="row">
                    <div class="col-md-4">';   
                        $temporada = Sds_com_configuracion::find()->where(["idconfiguracion" => $model->temporada])->one();
                        if ($temporada!=null)
                           echo $form->field($temporada, 'descripcion')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Temporada");                                     
                         echo 
                        '
                     </div> 
                     <div class="col-md-4">';
                        $fecha_entrega=$model->fecha_entrega;
                        if ($fecha_entrega==null)
                        {                           
                            $model->fecha_entrega= 'Fecha de entrega sin especificar';
                            echo $form->field($model, 'fecha_entrega')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Fecha Entrega");        
                        }
                        else
                        {
                            if (str_contains($fecha_entrega, ' ')) {
                                $unafecha_div = explode (" ",$fecha_entrega);
                                $una_fecha_1=trim($unafecha_div[0]); 
                                $una_hora=trim($unafecha_div[1]); 
        
                                $unafecha = explode ("-",$una_fecha_1);
                                $lafecha= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);  
        
                                $model->fecha_entrega= $lafecha; 
                            }
                            else
                            {
                                $unafecha = explode ("-",$model->fecha_entrega);
                                $lafecha= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);  
        
                                $model->fecha_entrega= $lafecha; 
                            }
                            
                            echo $form->field($model, 'fecha_entrega')->textInput(["readOnly"=>true,'style'=>'background-color:#ffffff']) ->label("Fecha Entrega");        
                        }
                        
                        echo '
                        </div>
                </div>';
               
                
            ?>                      
        <br>

    
    </div>
    <br>

        <?php ActiveForm::end(); ?>    
</div>






