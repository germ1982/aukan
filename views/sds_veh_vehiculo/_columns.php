<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$columna1 = '8%';
$columna2 = '15%';
$columna3 = '15%';
$columna4 = '15%';
$columna5 = '10%';
$columna6 = '10%';
$columna7 = '17%';
$columna8 = '15%';
return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dominio',
        'width' => $columna1,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'marca',    
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filter['marca'],        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Marca'],
        'width' => $columna2,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'modelo_descripcion',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filter['modelo'],        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Modelo'],
        'width' => $columna3,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo_descripcion',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filter['tipo'],        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo'],
        'width' => $columna4,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'anio',
        'width' => $columna5,
    ],
    [
        'class' => '\kartik\grid\BooleanColumn',
        'trueLabel' => 'Si',
        'falseLabel' => 'No',
        'attribute' => 'alquilado',
        'useSelect2Filter' => 'Alquilado',
        'value' => function ($model) {
            if ($model->alquilado != null || $model->alquilado == 1) {
                return true;
            } else {
                return false;
            }
        },
        'width' => $columna6,
        'filterInputOptions' => ['placeholder' => 'Alquilado'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado_descripcion',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filter['estado'],        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado'],
        'width' => $columna7,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {habilitaciones} {mantenimiento}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons'=>[
            'habilitaciones' => function ($url, $model) {
                $url =  Url::to(['/sds_veh_habilitacion', 'vehiculo' => $model->idvehiculo]);
                return Html::a('<i class="fas fa-archive"></i>', $url, [
                    'title' => 'Habilitaciones',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'mantenimiento' => function ($url, $model) {
                $url =  Url::to(['/sds_veh_mantenimiento', 'vehiculo' => $model->idvehiculo]);
                return Html::a('<span class= "fas fa-tools"></span>', $url, [
                    'title' => "Mantenimiento",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver Vehículo','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'],
        'width' => $columna8, 
    ],

];   