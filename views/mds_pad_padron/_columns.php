<?php
use yii\helpers\Url;

return [
    // [
    //     'class' => 'kartik\grid\CheckboxColumn',
    //     'width' => '20px',
    // ],
    // [
    //     'class' => 'kartik\grid\SerialColumn',
    //     'width' => '30px',
    // ],
    //     [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'idpadron',
    // ],
    
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'documento',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'apellido',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'calle',
        'filter' => '',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'altura',
        'filter' => '',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'circuito_anterior',
        'filter' => '',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'circuito_nuevo',
        'filter' => '',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'denominacion_circuito',
        'filter' => '',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'afiliacion',
        'label' =>'Afiliación',
        'value' => function ($model) {
            return $model->afiliacion == 'IND' ? 'INDEPENDIENTE' : 'MPN';
        },
        'filter' => ['IND' => 'INDEPENDIENTE', 'MPN' => 'MPN']
    ],
     [
         'class' => 'kartik\grid\ActionColumn',
         'dropdown' => false,
         'template' => '{view}',
         'vAlign'=>'middle',
         'urlCreator' => function($action, $model, $key, $index) { 
                 return Url::to([$action,'id'=>$key]);
         },
         'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
         /* 'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
         'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                           'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                           'data-request-method'=>'post',
                           'data-toggle'=>'tooltip',
                           'data-confirm-title'=>'Are you sure?',
                           'data-confirm-message'=>'Are you sure want to delete this item'],  */
     ],

];   