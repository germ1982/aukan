<?php

use app\models\Configuracion;
use yii\helpers\Url;

$columna_1 = '40%';
$columna_2 = '20%';
$columna_3 = '20%';
$columna_4 = '20%';


return [
     
    [
        'class' => '\kartik\grid\DataColumn',
        'width' => $columna_1,
        'attribute' => 'vehiculo',
        'value' => function ($model) {

            $marca = Configuracion::findOne($model->idmarca);

            return "$marca->descripcion $model->modelo $model->anio $model->color";
        },

        'headerOptions' => [
            'style' => 'color: #87b867;', // Cambia el color del texto del encabezado
        ],

    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_2,
        'attribute'=>'dominio',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_3,
        'attribute'=>'poliza',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_4,
        'attribute'=>'VTO', 
    ], 
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