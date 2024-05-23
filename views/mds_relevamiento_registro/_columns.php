<?php

use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idrelevamientoregistro',
        'label' => '#',
        'value' => function ($model) {
            return $model->idrelevamientoregistro;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcapaitem',
        'label' => 'Edificio',
        'value' => function ($model) {
            return $model->capaitem->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $edificiosFilter,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione'],
        'format' => 'raw'
    ],
    [
        'attribute' => 'fecha',
        'label' => 'Fecha',
        'value' => function ($model) {
            $date = date_create($model->fecha);
            $date = date_format($date, 'd-m-Y');
            return $date;
        },
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha',
            'options' => ['placeholder' => 'Fecha'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario_carga',
        'label' => 'Usuario carga',
        'value' => function ($model) {
            return mb_strtoupper($model->usuarioCarga->apellido . ' ' . $model->usuarioCarga->nombre);
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $usuariosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione'],
        'format' => 'raw'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observaciones',
        'format' => 'html',
        'filter' => false,
        'value' => function ($model) {
            $observaciones = $model->observaciones;
            if (strlen($observaciones) > 100) {
                return substr($observaciones, 0, 50) . "...";
            }
            return $observaciones;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'width' => '4%',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            return $model->deleted_at === NULL ? 'Si' : 'No';
        },
        'filter' => ['1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $stringButtonsIndex,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'buttons' => [
            'update' => function ($url, $model, $key) use ($usuarioAuth, $hasRolAdminGeneral) {
                if (!$model->deleted_at && ($hasRolAdminGeneral || ($model['idusuario_carga'] === $usuarioAuth->idusuario))) {
                    $url = Url::to(['mds_relevamiento_registro/update', 'id' => $model->idrelevamientoregistro]);
                    return Html::a('<span style="margin-left: 0.5rem" class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar'
                    ]);
                }
            },
            'delete' => function ($url, $model, $key) use ($usuarioAuth, $hasRolAdminGeneral) {
                if ((!$model->deleted_at && $model['idusuario_carga'] === $usuarioAuth->idusuario) || ($hasRolAdminGeneral)) {
                    $url =  Url::to(['/mds_relevamiento_registro/delete', 'id' => $model->idrelevamientoregistro]);
                    return  Html::a(
                        '<span style="margin-left: 0.5rem" class= "fas fa-trash"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Borrar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea eliminar el registro #' . $model->idrelevamientoregistro .
                                    '<br> del edificio <b>' . $model->capaitem->descripcion . '</b>?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'duplicate' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                if (!$model->deleted_at || $hasRolAdminGeneral) {
                    $url =  Url::to(['/mds_relevamiento_registro/duplicate', 'id' => $model->idrelevamientoregistro]);
                    return  Html::a(
                        '<span style="margin-left: 0.5rem" class= "fas fa-copy"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Duplicar registro'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea duplicar el registro #' . $model->idrelevamientoregistro .
                                    '<br> del edificio <b>' . $model->capaitem->descripcion . '</b>?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'print' => function ($url, $model) {
                $url =  Url::to(['/mds_relevamiento_registro/detalle_registro', 'idrelevamientoregistro' => $model->idrelevamientoregistro]);
                return  Html::a('<span style="margin-left: 0.5rem" class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF')
                ]);
            },
            'reactivate' => function ($url, $model) use ($hasRolAdminGeneral) {
                if ($model->deleted_at && $hasRolAdminGeneral) {
                    $url =  Url::to(['/mds_relevamiento_registro/reactivate', 'id' => $model->idrelevamientoregistro]);
                    return  Html::a('<span style="margin-left:0.5rem" class= "fas fa-check"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Re-activar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea re-activar este elemento?',
                            'method' => 'post',
                        ],
                    ]);
                }
            }
        ]
    ],
];
