<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_gis_capa_item;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_inv_entrega */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-inv-entrega-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        $model->idpersona=$idpersona;
    ?>
    <div class="row"> 
                    <div class="col-md-4">                    
                        <?=$form->field($model, 'idespecie')->dropDownList(
                            ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(91), 'idconfiguracion', 'descripcion'),
                            //['prompt' => 'Seleccionar Sexo ...', 'disabled' => true]
                            ['prompt' => 'Sel. Especie ...', 'disabled' => false,'id' => 'especie'],
                            
                        )?>                       
            
                    </div> 
                    <div class="col-md-4">            
                        <?= $form->field($model, 'cantidad')->textInput(["id" => "cantidad"])->label("Cantidad") ?>
                    </div>  
                    
                    <div class="col-md-4"> 

                            <?= $form->field($model, 'idlugar')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(                                        
                                    Sds_gis_capa_item::find()->where("idcapaitem in(select 
                                    sds_gis_capa_item.idcapaitem                      
                                    from sds_gis_capa_item, sds_gis_item_tematica 
                                    where 
                                    sds_gis_capa_item.idcapaitem=sds_gis_item_tematica.iditem and
                                    sds_gis_item_tematica.idtematica=2177)")
                                    ->orderBy(['descripcion' => SORT_ASC])
                                    ->all(),

                                    'idcapaitem',
                                    'descripcion'
                                    ),
                                            
                                'options' => ['placeholder' => 'Seleccionar Organismo ...'],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ])->label('Lugar de Entrega');
                        ?>
                    </div>    

                    <div class="col-md-3">                    
                        <?=$form->field($model, 'temporada')->dropDownList(
                             ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::INV_ENTREGA_TEMPORADA, false),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                            ['prompt' => 'Temporada ...', 'disabled' => false,'id' => 'temporada'],              
                        )?>                       
                    </div> 

                    <div class="col-md-3"> 
                    <?php 
                        $fc = date_create($model->fecha_entrega);
                        $model->fecha_entrega = date_format($fc, 'd/m/Y'); 
                    ?>

                    <?=  $form->field($model, 'fecha_entrega')->widget(DatePicker::ClassName(), [                                
                            
                            'language' => 'es',
                            'readonly' => false,
                            // 'layout' => '{picker}{input}{remove}',
                            'layout' => !$model->isNewRecord ? '{picker}{input}' : '{picker}{input}',
                            'options' => [
                                'id' => 'fecha_entrega',
                            ],
                            
                            'pluginOptions' => [                                    
                                'value' => null,
                                'format' => 'dd/mm/yyyy',
                                //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ]
                        ])
                        ->label('Fecha de Entrega')
                        ;?>
                    </div>   
                </div> 
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
