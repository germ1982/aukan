<?php

use app\controllers\Edificio_conectividadController;
use app\models\EdificioConectividad;
use app\models\Localidades;
use app\models\Provincias;
use yii\helpers\Html;
use yii\helpers\Url;

$columna1 = "5%";
$columna2 = "20%";
$columna3 = "25%";
$columna4 = "40%";
$columna5 = "10%";

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idedificio',
        'width' => $columna1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion_fija',
        'width' => $columna2,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion_gestion',
        'width' => $columna3,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlocalidad',
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'conexion',
        'value' => function ($model) {
            $cant = EdificioConectividad::findBySql("SELECT count(*) as conexion FROM edificio_conectividad WHERE idedificio = $model->idedificio")->one()->conexion;
            $aux = 'NO';
            if($cant > 0){
                $aux = "SI ($cant)";
            } 
            return $aux;
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'width' => $columna5,
        'template' => '{view} {update} {conectividad} ',

        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Eliminar',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Esta Seguro?',
            'data-confirm-message' => 'Esta seguro de eliminar este item?'
        ],
        'buttons' => [


        'conectividad' => function ($url, $model) {


            return Html::a(
                '<i class="fas fa-plus"></i>',
                ['edificio_conectividad/create','idedificio' => $model->idedificio],
                [
                    'data-pjax' => 1,
                    'class' => '',
                    'role' => 'modal-remote',
                    'title' => 'Nueva Conectividad'
                ]
            );
        },

    ],
    ],
    

];
