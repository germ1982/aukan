<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idintervencion',
        'width' => '5%',
        'enableSorting' => false,
        'visible' => (Yii::$app->request->isAjax) ? false : true,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idmovimiento',
        'width' => '10%',
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_movimiento',
        'width' => '30%',
        'value' => function ($model) {
            $value = "";
            if ($model && isset($model->tipoMovimiento)) {
                $value = $model->tipoMovimiento->descripcion;
            }
            return $value;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $username = "";
            if ($model && isset($model->idUsuario->apellido) && isset($model->idUsuario->nombre)) {
                $username = $model->idUsuario->apellido . ", " . $model->idUsuario->nombre;
            }
            return $username;
        },
        'filter' => false,
        'width' => '30%',
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'width' => '5%',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            return $model->deleted_at === NULL ? 'Si' : 'No';
        },
        'enableSorting' => false
        // 'filter' => ['1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update} {delete} {reactivate}',
        'viewOptions' => ['role' => (Yii::$app->request->isAjax) ? 'modal-remote' : 'post', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => (Yii::$app->request->isAjax) ? 'modal-remote' : 'post', 'title' => 'Actualizar', 'data-toggle' => 'tooltip'],

        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },

        'visibleButtons' => [
            'update' => function ($model) use ($hasRolAdminGeneral, $estaAtendida) {
                return  is_null($model->deleted_at) && ($estaAtendida || $hasRolAdminGeneral);
            },
            'reactivate' => function ($model)  use ($hasRolAdminGeneral) {
                return ($hasRolAdminGeneral && !is_null($model->deleted_at));
            },
            'delete' => function ($model) use ($hasRolAdminGeneral, $estaAtendida) {
                return is_null($model->deleted_at) && ($estaAtendida || $hasRolAdminGeneral);
            },
        ],

        'buttons' => [
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_intervencion_movimiento/delete', 'id' => $model->idmovimiento]);
                return  Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Eliminar',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-pjax' => 1,
                    'data' => [
                        'confirm' => '¿Está seguro que desea eliminar este elemento?',
                        'method' => 'post',
                    ],
                ]);
            },
            'reactivate' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_intervencion_movimiento/reactivate', 'id' => $model->idmovimiento]);
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
        ]
    ],
];
