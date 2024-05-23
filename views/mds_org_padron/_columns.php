<?php

use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mes',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'anio',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'legajo',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idunidadoperativa',
        'value' => function ($model) {
            switch ($model->idunidadoperativa) {
                case 1:
                    return "MINISTERIO DE DESARROLLO SOCIAL Y TRABAJO";
                case 2:
                    return "SUBSECRETARIA DE DESARROLLO SOCIAL";
                case 3:
                    return "SUBSECRETARIA DE FAMILIA";
                case 4:
                    return "SUBSECRETARIA DE TRABAJO";
            }
        },
        'filter' => [
            1 => 'MINISTERIO DE DESARROLLO SOCIAL Y TRABAJO',
            2 => 'SUBSECRETARIA DE DESARROLLO SOCIAL',
            3 => 'SUBSECRETARIA DE FAMILIA',
            4 => 'SUBSECRETARIA DE TRABAJO',
        ]
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'categoria',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido_nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cuil',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'eventual',
        'value' => function ($model) {
            if ($model->eventual) {
                return "Si";
            } else {
                return "No";
            }
        },
        'filter' => ['0' => 'No', '1' => ' Si']
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];
