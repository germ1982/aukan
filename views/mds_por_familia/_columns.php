<?php

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
   /* [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idfamilia',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'localidad',
    ],
       [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cuil',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dni',
        'width' => '8%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
        'width' => '29%',
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'importe',
         'width' => '8%',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'responsable_cobro',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'dni_responsable',
    // ],
    
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'programa',    
         'width' => '20%',    
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
       'attribute'=>'subprograma',
       'width' => '22%',

    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'area',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'responsable_certificacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'expediente',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'desde',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'hasta',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'F12',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'F15',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'F16',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'F17',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'F18',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'F19',
    // ],
    [
         'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'mes',
        'width' => '5%',
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'anio',
         'width' => '8%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'post','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   