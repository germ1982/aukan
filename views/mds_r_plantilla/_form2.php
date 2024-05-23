<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_gis_capa;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;



/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_plantilla */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="mds-r-plantilla-form">
 <?php $form = ActiveForm::begin(); ?>
  <div class="input-group" 
  style="align-items: end"> 
   
    <?= $form->field($model, 'idtipoplantilla')
                    ->widget(Select2::classname(), 
                    [
                    'data' => ArrayHelper::map(
                        Sds_com_configuracion ::find()
                        
                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: R_TIPO_PLANTILLA])
                        ->orderBy(['descripcion' => SORT_ASC])
                        ->all(),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccionar...', 
                    'id' => 'cmb_idtipoplantilla',
                ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label('Tipo Plantilla');
                ?>
            </span>
           
            <span class="input-group-btn">
            <?php 
            echo Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create_plantilla'],
                        ['role'=>'modal-remote','title'=> 'Crear nueva plantilla','class'=>'btn btn-default']);
        ?>
            </span>                
</div>
<div class="input-group">
<?= $form->field($model, 'variable_diagnostico')
                    ->widget(Select2::classname(), 
                    [
                    'data' => ArrayHelper::map(
                        Sds_com_configuracion :: find()
                        
                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: DIAGNOSTICO_INDICADOR])
                        ->orderBy(['descripcion' => SORT_ASC])
                        ->all(),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccionar...', 
                    'id' => 'cmb_variable_diagnostico',
                ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label('Variable Diagnostico');
                ?>

           <span class="input-group-btn">
            <?php 
            echo Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create_diagnostico'],
                        ['role'=>'modal-remote','title'=> 'Crear nueva variable','class'=>'btn btn-default']);
        ?>
</div>

<?=
                                            $form->field($model, 'dimensiones')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map(
                                                    Sds_com_configuracion_tipo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                                    'idconfiguraciontipo',
                                                    'descripcion',
                                                ),
                                                'options' => ['id' => 'dimensiones', 'placeholder' => 'Dimensiones', 'multiple' => true],
                                                'size' => Select2::MEDIUM,
                                                'pluginOptions' => [
                                                    'tags' => true,
                                                    'tokenSeparators' => [',', ' '],
                                                    //'maximumInputLength' => 50,
                                                    'allowClear' => true
                                                ],
                                                
                                                ])->label('Dimensiones asociadas a la variable seleccionada');
                                            ?>

<?= $form->field($model, 'origen')
                    ->widget(Select2::classname(), 
                    [
                    'data' => ArrayHelper::map(
                        Sds_com_configuracion :: find()
                        
                        ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo :: R_ORIGEN])
                        ->orderBy(['descripcion' => SORT_ASC])
                        ->all(),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccionar...', 
                    'id' => 'cmb_origen',
                    'onchange' => 'verTipoDisp();',
                ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label('Origen');
                ?>

<div style="display:none" id="div_gis_capa">
  <?= $form->field($model, 'id_gis_capa')
                    ->widget(Select2::classname(), 
                    [
                    'data' => ArrayHelper::map(
                        Sds_gis_capa ::find()
                        ->orderBy(['descripcion' => SORT_ASC])
                        ->all(),
                        'idcapa',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccionar...', 
                    'id' => 'cmb_gis_capa',
                ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
                ->label('Tipo dispositivo');
                ?>
</div>                


    <?php ActiveForm::end(); ?>
    
</div>

<?php
$script = <<<  JS

function verTipoDisp() {
    if ($("#cmb_origen").val()== 4093){
        $("#div_gis_capa").show();
    }
    else{
        $("#div_gis_capa").hide();
    }
    
    
}

JS;

$this->registerJs($script);

?>