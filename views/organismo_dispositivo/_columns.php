<?php
use yii\helpers\Url;

return [
    /* [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ], */
   /*  [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ], */
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iddispositivo',
        'width' => '1%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idorganismo',
        'width' => '1%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'es_oficial',
        'value' => function ($model) {
                  return $model->es_oficial == 1 ? 'Si' : 'No';
            },
            'filter' => ['0' => 'No', '1' => ' Si'],
            'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'es_organismo',
        'value' => function ($model) {
                  return $model->es_organismo == 1 ? 'Si' : 'No';
            },
            'filter' => ['0' => 'No', '1' => ' Si'],
            'width' => '10%',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'activo',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'direccion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'alias',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'idcapaitem',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'telefono',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => '{view} {update} ',
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