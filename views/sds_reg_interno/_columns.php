<?php

use yii\helpers\Url;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idinterno',
        'width'=>'7%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'organismo'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'edificio',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dispositivo'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'grupo',
        'width'=>'7%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'responsable',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'recepcion',
        'value'=>function($model){return $model->recepcion==1?'Si':'No';},
        'filter'=>['0'=>'No', '1'=> ' Si']
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
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
                          'data-confirm-title'=>'Está a punto de eliminar el interno. ¿Está seguro de hacerlo?',
                          'data-confirm-message'=>'<span class="text-danger">Haga click en OK para eliminarlo</span>'], 
    ],

];   