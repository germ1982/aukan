<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_ejidos;
use app\models\Sds_com_configuracion;
use app\models\Sds_gis_capa_item;
use app\models\Mds_r_plantilla;
use app\models\Mds_r_planilla;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_diagnostico */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-r-diagnostico-form">

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
                    
                    echo  $form->field($model, 'idejido')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Mds_r_ejidos::find()
                            ->orderBy(['ejido' => SORT_ASC])
                            ->all(),
                            'idejido',
                            'ejido'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_ejido',
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Ejido");
                
                }
                else
                {
                    if ($una_dimension->origen==Mds_r_variable_dimension::ORIGEN_DISPOSITIVO)
                    {
                                            
                        $una_planilla= Mds_r_planilla::find()
                        ->where(['idplanilla' => $una_dimension->idplanilla])        
                        ->one();
                        

                        $una_plantilla= Mds_r_plantilla::find()
                        ->where(['idtipoplantilla' => $una_planilla->idplantilla])        
                        ->one();
                        
                        
                        echo  $form->field($model, 'iddispositivo')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Sds_gis_capa_item::find()
                                //->where(['idcapa' => $una_plantilla->id_gis_capa]) 
                                ->where(['idcapa' => $una_dimension->id_giscapa]) 
                                ->orderBy(['descripcion' => SORT_ASC])
                                ->all(),
                                'idcapaitem',
                                'descripcion'
                            ),
                            'options' => ['placeholder' => 'Seleccionar ...', 
                            'id' => 'cmb_dispositivo',
                            ],
    
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])
                        ->label("Dispositivo");

                    }
                }
            
            ?>        

        </div>  
    </div>  
    <div class="row">
        <div class="col-md-8">                   
               <?php                      
                    echo  $form->field($model, 'valor_dimension')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => $una_dimension->iddimension])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_dimension',
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Dimensión");

                ?>
               
        </div>    
        <div class="col-md-4">    
                <?= $form->field($model, 'valor')->textInput() ?>
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

	