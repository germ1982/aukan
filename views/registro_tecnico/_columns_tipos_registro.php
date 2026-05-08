<?php

use yii\helpers\Html;
use yii\helpers\Url;

return [
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_configuracion',
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'width' => '80%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {

            if ($model->activo == '1') {
                return Html::a(
                    '<span class= "fa fa-circle"></span>',
                    ['registro_tecnico/desactivar_configuracion', 'id' => $model->id_configuracion],
                    [
                        'role' => 'modal-remote',
                        'style' => 'color:green;', // Added inline style
                        'class' => 'btn neon btn-xs',
                        'data-confirm' => false,
                        'data-method' => false,
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'title' => 'Desactivar'
                    ]
                ) . ' SI';
            } else {
                return Html::a(
                    '<span class= "fa fa-ban"></span>',
                    ['registro_tecnico/activar_configuracion', 'id' => $model->id_configuracion],
                    [
                        'role' => 'modal-remote',
                        'style' => 'color:red;', // Added inline style
                        'class' => 'btn neon btn-xs',
                        'data-confirm' => false,
                        'data-method' => false,
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'title' => 'Activar'
                    ]
                ) . ' NO';
            }
        },
        'filter' => [1 => 'Sí', 0 => 'No'],
        'filterInputOptions' => [
            'id' => 'filtro-activo', // Opcional: un ID único
            'class' => 'form-control',
            'prompt' => 'Todos', // AQUÍ es donde va el prompt
        ],
        'width' => '10%',
        'format' => 'raw',
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{update}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to(['configuracion/' . $action, 'id' => $key]);
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
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/configuracion/update_tipo', 'id' => $model->id_configuracion]);
                return Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => "Editar ",
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
    ],

];
