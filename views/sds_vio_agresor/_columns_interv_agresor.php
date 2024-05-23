<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'label' => 'DNI',
        'enableSorting' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'enableSorting' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'parentezco',
        'label' => 'Parentesco',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'label' => 'Activo',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            if ($model['activo'] == 1)
                return "Si";
            else
                return "No";
        },
        // 'filter' => ['0' => 'No', '1' => 'Si'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update} {eliminar} {reactivate}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'idintervencion, $idagresor' => $key]);
        },

        'visibleButtons' => [
            'update' => function ($model)  use ($hasRolAdminGeneral, $estaAtendida) {
                return ($hasRolAdminGeneral || $estaAtendida);
            },
            'eliminar' => function ($model) use ($hasRolAdminGeneral, $estaAtendida) {
                return ($model['activo'] === 1) && ($hasRolAdminGeneral || $estaAtendida);
            },
            'reactivate' => function ($model)  use ($hasRolAdminGeneral) {
                return ($model['activo'] === 0 && $hasRolAdminGeneral);
            },
        ],

        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/update', 'id' => $model['idagresor'], 'idintervencion' => $model['idintervencion']]);
                return  Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'data-pjax' => 1,
                    'role' => 'modal-remote',
                    'title' => 'Editar',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'eliminar' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/delete', 'id' => $model['idagresor'], 'idintervencion' => $model['idintervencion']]);
                return  Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'title' => 'Borrar',
                    'data-request-method' => 'post',
                    'data-pjax' => 1,
                    'data' => [
                        'confirm' => '¿Está seguro que desea eliminar este elemento?',
                        'method' => 'post',
                    ],
                ]);
            },
            'view' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/view', 'id' => $model['idagresor'], 'idintervencion' => $model['idintervencion']]);
                return  Html::a(' <span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'data-pjax' => 1,
                    'role' => 'modal-remote',
                    'title' => 'Ver',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'reactivate' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/reactivate', 'id' => $model['idagresor'], 'idintervencion' => $model['idintervencion']]);
                return  Html::a(
                    '<span style="margin-left:0.5rem" class= "fas fa-check"></span>',
                    $url,
                    [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Re-activar'),
                        'data-request-method' => 'post',
                        'data-pjax' => 1,
                        'data' => [
                            'confirm' => '¿Está seguro que desea re-activar este elemento?',
                            'method' => 'post',
                        ],
                    ]
                );
            }
        ],
    ],
];
