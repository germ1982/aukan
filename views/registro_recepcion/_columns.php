<?php

use yii\helpers\Url;

$columna_1 = '8%';
$columna_2 = '8%';
$columna_3 = '8%';
$columna_4 = '8%';
$columna_5 = '58%';
$columna_6 = '10%';

return [
    /* [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ], */
    /* [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_registro_recepcion',
        'width' => $columna_1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha',
        'width' => $columna_2,
        'format' => 'raw',  // Especificamos que será raw para que el formateo se aplique a la fecha
        'value' => function ($model) {
            return Yii::$app->formatter->asDate($model->fecha, 'php:d/m/Y');
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'hora',
        'width' => $columna_3,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'width' => $columna_4,
    ],
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'motivo',
    ], */
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'acceso',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dispositivoDescripcion',
        'width' => $columna_5,
        'label' => 'Dispositivo Derivacion',
        'value' => function ($model) {
            return $model->dispositivoDerivacion ? $model->dispositivoDerivacion->descripcion : '(No asignado)';
        },
    ],



    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_responsable_derivacion',
    ], */
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id_tipo_recepcion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'observacion',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {delete}',
        'vAlign' => 'middle',
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
