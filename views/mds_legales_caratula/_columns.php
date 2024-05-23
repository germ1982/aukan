<?php

use yii\helpers\Html;
use yii\helpers\Url;

$layoutDate = <<< HTML
    {input1}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlegalescaratula',
        'label' => '#',
        'value' => function ($model) {
            return $model['idlegalescaratula'];
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'caratula',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'numero_expediente',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'caso',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'anio_expediente',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'visible' => ($hasRolAdminGeneral),
        'value' => function ($model) {
            if ($model->deleted_at === null)
                return "Si";
            else
                return "No";
        },
        'filter' => ['2' => 'Todos', '1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $string,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['data-pjax' => 0, 'role' => 'post', 'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'style' => 'margin-left: 0.5rem'],
        'buttons' => [
            'listadoRequerimientos' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_legales_caratula/listado_requerimientos', 'idlegalescaratula' => $model->idlegalescaratula
                ]);
                return Html::a('<i style="margin-left: 0.5rem" class="fas fa-list"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Listado de requerimientos',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'delete' => function ($url, $model, $key) use ($hasRolAdminGeneral, $usuarioAuth) {
                $url =  Url::to(['/mds_legales_caratula/delete', 'id' => $model->idlegalescaratula]);
                if (($hasRolAdminGeneral || ($model['idusuario_alta'] === $usuarioAuth->idusuario)) && !$model->deleted_at) {
                    return  Html::a(
                        '<span style="margin-left: 0.5rem" class= "fas fa-trash"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Borrar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea eliminar este elemento?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'reactivate' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                if ($model['deleted_at'] && ($hasRolAdminGeneral)) {
                    $url =  Url::to(['/mds_legales_caratula/reactivate', 'id' => $model->idlegalescaratula]);
                    return  Html::a(
                        '<span style="margin-left:0.5rem" class= "fas fa-check"></span>',
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
        ]
    ],
];
