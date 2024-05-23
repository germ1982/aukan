<?php
use yii\helpers\Url;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_r_plantilla;

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

return [
   /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idplantilla',
        'label'=>'ID',
        
    ],
    [
        'class' => '\kartik\grid\DataColumn',
            'attribute' => 'activo',
            'label' => 'Activo',
            
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idtipoplantilla',
        'label'=>'Tipo plantilla',
        'value' => function ($model) {
            $tipo = Sds_com_configuracion::findOne($model->idtipoplantilla);

            return $tipo->descripcion;                        
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::find()            
            ->orderBy(['descripcion' => SORT_ASC])
            ->where("idconfiguraciontipo=".Sds_com_configuracion_tipo::R_TIPO_PLANTILLA)
            ->andWhere("activo=1")
            ->all(), 'idconfiguracion', 'descripcion'),            
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo plantilla...'],
        'format' => 'raw',
        'width' => '25%',          
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'variable_diagnostico',
        'label'=>'Variable diagnostico',
        'value' => function ($model) {
            $tipo = Sds_com_configuracion::findOne($model->variable_diagnostico);

            return $tipo->descripcion;                        
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::find()            
            ->orderBy(['descripcion' => SORT_ASC])
            ->where("idconfiguraciontipo=".Sds_com_configuracion_tipo::DIAGNOSTICO_INDICADOR)            
            ->all(), 'idconfiguracion', 'descripcion'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Variable diagnostico...'],
        'format' => 'raw',
        'width' => '25%',   
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dimension',
        'label'=>'Dimension',
        'value' => function ($model) {
            $tipo = Sds_com_configuracion_tipo::findOne($model->dimension);

            return $tipo->descripcion;                        
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion_tipo::find()            
            ->orderBy(['descripcion' => SORT_ASC])
            ->all(), 'idconfiguraciontipo', 'descripcion'), 
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Dimension...'],
        'format' => 'raw',
        'width' => '25%',    
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'origen',
        'label'=>'Origen',
        'value' => function ($model) {
            $tipo = Sds_com_configuracion::findOne($model->origen);

            return $tipo->descripcion;                        
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_configuracion::find()            
            ->orderBy(['descripcion' => SORT_ASC])
            ->where("idconfiguraciontipo=".Sds_com_configuracion_tipo::R_ORIGEN)
            ->all(), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Origen...'],
        'format' => 'raw',
        'width' => '25%',  
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Eliminar plantilla',
                          'data-confirm-message'=>'¿Está seguro que quiere eliminar esta plantilla?'], 
    ],
];   