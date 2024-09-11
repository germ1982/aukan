<?php

use app\models\Persona;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre_apellido', // Debe ser el nombre del atributo virtual
        'value' => function ($model) {
            return $model->apellido . ' ' . $model->nombre;
        },

        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento_tipo',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nacionalidad',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'genero',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'fecha_nacimiento',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'nombre',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'apellido',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'padre',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'conviviente',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'domicilio',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'domicilio_calle',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'domicilio_numero',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'idlocalidad',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update} ',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Delete',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];
