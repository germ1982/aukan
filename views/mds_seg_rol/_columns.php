<?php

use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idrol',
        'width' => '10%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'value' => function ($model) {
            return $model->deleted_at ? 'No' : 'Si';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{permisos} {usuarios} {view} {update} {delete} {reactivate} ',
        'width' => '10%',
        'dropdown' => false,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'buttons' => [
            'permisos' => function ($url, $model) {
                $url = Url::to(['/mds_seg_permiso/index', 'idrol' => $model->idrol]);
                if (!$model->deleted_at) {
                    return Html::a('<i class="fas fa-unlock-alt"></i>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Administrar Permisos',
                        'data-toggle' => 'tooltip'
                    ]);
                }
            },
            'usuarios' => function ($url, $model) {
                $url = Url::to(['/mds_seg_rol/usuarios', 'idrol' => $model->idrol]);
                return Html::a('<span style="margin-left: 0.5rem" class="fas fa-users"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Ver usuarios con este rol',
                    'data-toggle' => 'tooltip'
                ]);
            },
            'delete' => function ($url, $model, $key) {
                $url = Url::to(['/mds_seg_rol/delete', 'id' => $model->idrol]);
                if (!$model->deleted_at) {
                    return Html::a(
                        '<span class="fas fa-trash"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Borrar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea eliminar este rol?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'reactivate' => function ($url, $model, $key) {
                if ($model->deleted_at) {
                    $url =  Url::to(['/mds_seg_rol/reactivate', 'idrol' => $model->idrol]);
                    return  Html::a(
                        '<span class= "fas fa-check"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Re-activar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea re-activar este elemento?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },

        ],
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => '¿Esta seguro que desea eliminar el rol seleccionado?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],
];
