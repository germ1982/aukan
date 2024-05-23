<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_periodo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-r-periodo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-2">    
                    <?= $form->field($model, 'mes')->textInput(['maxlength' => true])->label("Mes") ?>
            </div>   
            <div class="col-md-3">   
                <?= $form->field($model, 'periodo')->textInput(['maxlength' => true])->label("Periodo") ?> 
            </div>  
            <div class="col-md-3">    
        
                
                    <?= $form->field($model, 'indicador_diagnostico')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::DIAGNOSTICO_INDICADOR, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_indicador',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Indicador Diagnóstico");
                    ?>
            </div>    
            <div class="col-md-3">                            
                    <?= $form->field($model, 'tipo_mapa')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::find()
                            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::DIAGNOSTICO_TIPO_MAPA, 'activo'=>'1'])
                            ->orderBy(['descripcion' => SORT_ASC])
                            ->all(),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => ['placeholder' => 'Seleccionar ...', 
                        'id' => 'cmb_tipomapa',
                         ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ->label("Tipo de Mapa");
                    ?>
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
