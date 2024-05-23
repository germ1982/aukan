<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
?>

<style>
    td>a>span {
        margin-left: 0.5rem;
    }
</style>

<?php
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idhistorial',
        'width' => '4%',
        'value' => function ($model) {
            return $model->idhistorial;
        },
        'filterInputOptions' => ['class' => 'form-control']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpostulacion',
        'width' => '4%',
        'value' => function ($model) {
            return $model->idpostulacion;
        },
        'filterInputOptions' => ['class' => 'form-control']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observacion',
        'value' => function ($model) {
            $observacion = $model->observacion;
            if (strlen($observacion) > 30) {
                $observacion = mb_substr($observacion, 0, 20) . "...";
            }
            return $observacion;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observacion_publica',
        'value' => function ($model) {
            $observacionPublica = $model->observacion_publica;
            if (strlen($observacionPublica) > 30) {
                $observacionPublica = mb_substr($observacionPublica, 0, 20) . "...";
            }
            return $observacionPublica;
        }
    ],
    [
        'attribute' => 'created_at',
        'width' => '10%',
        'value' => function ($model) {
            if ($model->created_at) {
                $date = date_create($model->created_at);
                $date = date_format($date, 'd-m-Y');
            } else {
                $date = "";
            }
            return $date;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'created_at',
            'options' => ['placeholder' => 'Seleccione...'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'layout' => '{input}{remove}',
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado_anterior',
        'value' => function ($model) {
            return ($model->anteriorEstado) ? $model->anteriorEstado->descripcion : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $estadoAnteriorFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado_nuevo',
        'value' => function ($model) {
            return ($model->nuevoEstado) ? $model->nuevoEstado->descripcion : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $estadoNuevoFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $txt = "";
            if ($model->usuarioCarga) {
                $txt =  mb_strtoupper("{$model->usuarioCarga->nombre} {$model->usuarioCarga->apellido}");
            }
            return $txt;
        },
        'filterInputOptions' => ['class' => 'form-control']
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'width' => '6%',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            return is_null($model->deleted_at) ? 'Si' : 'No';
        },
        'filter' => ['1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => "{view}{update}{print}{delete}{reactivate}",
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'visibleButtons' => [
            'view' => function () use ($permission) {
                return ($permission['permissionRead']);
            },
            'update' => function () use ($permission) {
                return ($permission['permissionUpdate']);
            },
            'print' => function () use ($permission) {
                return ($permission['permissionRead']);
            },
            'delete' => function ($model) use ($permission) {
                return ($permission['permissionDelete'] && is_null($model->deleted_at));
            },
            'reactivate' => function ($model) use ($permission) {
                return ($permission['permissionReactivate'] && !is_null($model->deleted_at));
            },
            'estado' => function () use ($permission) {
                return ($permission['permissionUpdate']);
            },
            'historialestado' => function () use ($permission) {
                return ($permission['permissionUpdate']);
            },
        ],
        'buttons' => [
            'view' => function ($url, $model, $key) {
                $url = Url::to(['mds_conc_historial/view', 'id' => $model->idhistorial]);
                return Html::a('<span class="fas fa-eye"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Ver',
                    'target' => '_blank',
                ]);
            },
            'update' => function ($url, $model) {
                $url = Url::to(['/mds_conc_historial/update', 'id' => $model->idhistorial]);
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Actualizar',
                    'id' => 'bnt-update'
                ]);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/mds_conc_historial/delete', 'id' => $model->idhistorial]);
                return Html::a('<span class="fas fa-trash"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'title' => ('Borrar'),
                    'data' => [
                        'confirm' => '¿Está seguro que desea eliminar este elemento?',
                        'method' => 'post'
                    ],
                ]);
            },
            'reactivate' => function ($url, $model) {
                $url =  Url::to(['/mds_conc_historial/reactivate', 'id' => $model->idhistorial]);
                return  Html::a('<span class= "fas fa-check"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'title' => ('Re-activar'),
                    'data' => [
                        'confirm' => '¿Está seguro que desea re-activar este elemento?',
                        'method' => 'post',
                    ],
                ]);
            },
            'print' => function ($url, $model) {
                $url =  Url::to(['/mds_conc_historial/reporte', 'id' => $model->idhistorial]);
                return  Html::a('<span style="margin-left:0.5rem" class="fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF'),
                    'target' => '_blank',
                ]);
            },

        ]
    ],

];
