<?php
use yii\helpers\Url;
use kartik\grid\GridView;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion_base',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filter['articulos'],        
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'placeholder' => 'Articulo Base',
                'allowClear' => true
            ],
        ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion_convertido',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filter['articulos'],        
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'placeholder' => 'Articulo Convertido',
                'allowClear' => true
            ],
        ],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{update}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   