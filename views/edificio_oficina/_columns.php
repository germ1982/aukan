<?php

use app\models\Edificio;
use app\models\Persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$columna1 = "5%";
$columna2 = "35%";
$columna3 = "40%";
$columna4 = "10%";
$columna5 = "10%";


$mysql_edificios = "SELECT idedificio, concat(descripcion_fija,' - ',descripcion_gestion) as descripcion_fija from edificio
                        where idedificio in (select idedificio from edificio_oficina ) 
                        order by descripcion_fija, descripcion_gestion";

return [
[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idoficina',
        'width' => $columna1,

    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
        'width' => $columna2,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idedificio',
        'value' => function ($model) {
            $id = $model->idedificio;
            if ($id != null) {
                $edificio = Edificio::findOne($id);
                return "$edificio->descripcion_fija - $edificio->descripcion_gestion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Edificio::findBySql($mysql_edificios)->all(), 'idedificio', 'descripcion_fija'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'edificio...'],
        'format' => 'raw',
        'width' => $columna3,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            return $model->activo == 1 ? 'Si' : 'No';
        },
        'filter' => ['0' => 'No', '1' => ' Si'],
        'width' => $columna4,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => '{view} {update} ',
        'width' => $columna5,
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