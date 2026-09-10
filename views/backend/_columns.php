<?php

use yii\helpers\Url;

return [
/*     [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ], */
/*     [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idbackend',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'endpoint',
        'format' => 'raw',
        'value' => function ($model) {
            return \yii\helpers\Html::a(
                $model->endpoint,
                $model->endpoint,
                [
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer',
                ]
            );
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
    ],
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
