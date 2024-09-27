<?php

use app\models\Localidades;
use app\models\Provincias;
use yii\helpers\Url;

$columna1 = "5%";
$columna2 = "20%";
$columna3 = "25%";
$columna4 = "40%";
$columna5 = "10%";

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idedificio',
        'width' => $columna1,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion_fija',
        'width' => $columna2,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion_gestion',
        'width' => $columna3,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idlocalidad',
        'value' => function ($model) {
            $localidad = Localidades::findOne($model->idlocalidad);
            $provincia = Provincias::findOne($localidad->id_provincia);
            return "$localidad->localidad ($provincia->provincia) $model->direccion_calle $model->direccion_altura $model->direccion";
        },
        'format' => 'raw',
        'width' => $columna4,
        'label' => 'Direccion',
    ],
    
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'width' => $columna5,
        'template' => '{view} {update} ',
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