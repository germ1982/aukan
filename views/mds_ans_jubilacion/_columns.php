<?php
use yii\helpers\Url;

return [
/*  [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idjubilacion',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo_dni',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dni',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cuil',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre_apellido',
    ],
     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'beneficio_grupo',//ANOTEZE: ACA CAMBIO Y COMPARO POR LA NUEVA VARIABLE QUE AGREGUE: beneficio_grupo
        'value' => function ($model) {            
            return $model->beneficio_grupo==0 ? 'Jubilación' : 'Pensión';
        },
        'width' => '12%',
        'filter' => ['0' => 'Jubilación', '1' => 'Pensión']      
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'periodo',
     ],
    [ 
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view}', //poniendo esto, no habilita modificar y eliminar
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