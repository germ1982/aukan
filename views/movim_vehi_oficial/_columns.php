<?php

use app\models\Empleado;
use app\models\VehiculoOficial;
use yii\helpers\Url;

$columna_1 = '4%';
$columna_2 = '28%';
$columna_3 = '28%';
$columna_4 = '20%';
$columna_5 = '20%';



return [
    
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_1,
        'attribute'=>'idmovimiento',
        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_2,
        'attribute'=>'idvehiculo',
        'value' => function ($model) {

            $vehiculo = VehiculoOficial::getVehiculoOficial($model->idvehiculo);

            return "$vehiculo";
        },
    ],
    
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_3,
        'attribute'=>'chofer',
        'value' => function ($model) {

            $choferInformacion = Empleado::get_empleado($model->chofer)->descripcion;

            return "$choferInformacion";
        },

        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_4,
        'attribute'=>'salida',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_5,
        'attribute'=>'regreso',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'finalidad_viaje',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'lugar',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'hora',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'kilometraje',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn' ,
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   