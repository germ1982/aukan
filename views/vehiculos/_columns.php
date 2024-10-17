<?php
use yii\helpers\Url;

return [
 /*    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ], */
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idvehiculo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idempleado',
    ],
   /*  [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idpersona',
    ], */
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dominio',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idmarca',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'modelo',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'color',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'vehiculo_oficial',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{view} {update} ',
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