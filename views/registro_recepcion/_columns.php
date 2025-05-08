<?php

use yii\helpers\Url;



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
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha',
        'format' => 'raw',  // Especificamos que será raw para que el formateo se aplique a la fecha
        'value' => function ($model) {
            return Yii::$app->formatter->asDate($model->fecha, 'php:d/m/Y');
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'hora',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
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
        'attribute' => 'id_dispositivo_derivacion',
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
