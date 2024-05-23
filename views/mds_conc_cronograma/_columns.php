<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
?>

<style>
    td>a>span {
        margin-left: 0.5rem
    }
</style>

<?php
return
    [

        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idetapa',
            'width' => '5%',
            'value' => function ($model) {
                return $model->idetapa;
            },
            'filterInputOptions' => ['class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idconcurso',
            'width' => '17%',
            'value' => function ($model) {
                return ($model->concurso) ? $model->concurso->descripcion : '';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $concursosFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'multiple' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'nombre',
            'width' => '20%',
            'value' => function ($model) {
                $data = "";
                if ($model->nombre) {
                    $nombre = mb_strtoupper($model->nombre);
                    $data = $nombre;
                }
                return strtoupper($data);
            },
            'filterInputOptions' => ['placeholder' => 'Nombre', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'orden',
            'width' => '5%',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'estado',
            'width' => '9%',
            'value' => function ($model) {
                return $model->estado ? "Activo" : "Inactivo";
            },
            'filter' => ['1' => 'Activo', '0' => 'Inactivo'],
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'fecha_inicio',
            'width' => '11%',
            'value' => function ($model) {
                if ($model->fecha_inicio) {
                    $fr = date_create($model->fecha_inicio);
                    $fr = date_format($fr, 'd/m/Y H:i');
                } else {
                    $fr = '';
                }
                return $fr;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_inicio',
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
            'attribute' => 'fecha_fin',
            'width' => '11%',
            'value' => function ($model) {
                if ($model->fecha_fin) {
                    $fr = date_create($model->fecha_fin);
                    $fr = date_format($fr, 'd/m/Y H:i');
                } else {
                    $fr = '';
                }
                return $fr;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_fin',
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
            'attribute' => 'idusuario',
            'width' => '10%',
            'value' => function ($model) {
                $username = "";
                if ($model && isset($model->usuarioCarga)) {
                    $username = mb_strtoupper($model->usuarioCarga->apellido) . ", " . mb_strtoupper($model->usuarioCarga->nombre);
                }
                return $username;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $usuarioCargaFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'multiple' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Usuario...'],
            'format' => 'raw',
        ],
        [
            'attribute' => 'created_at',
            'width' => '11%',
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
            'attribute' => 'deleted_at',
            'width' => '4%',
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
            'hAlign' => 'center',
            'width' => '10%',
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
            ],
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $url = Url::to(['mds_conc_cronograma/view', 'id' => $model->idetapa]);
                    return Html::a('<span class="fas fa-eye"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Ver',
                        'target' => '_blank',
                    ]);
                },
                'update' => function ($url, $model) {
                    $url = Url::to(['/mds_conc_cronograma/update', 'id' => $model->idetapa]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar',
                        'id' => 'bnt-update'
                    ]);
                },
                'delete' => function ($url, $model) {
                    $url =  Url::to(['/mds_conc_cronograma/delete', 'id' => $model->idetapa]);
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
                    $url =  Url::to(['/mds_conc_cronograma/reactivate', 'id' => $model->idetapa]);
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
            ]
        ]
    ];
?>