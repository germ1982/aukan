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
            'class' => 'yii\grid\CheckboxColumn',
            'header' => '',
            'headerOptions' => ['style' => 'width:3%'],
            'checkboxOptions' =>
            function ($model) {
                return [
                    'value' => $model->idsolicitud,
                    'class' => 'checkbox-row', 'id' => 'checkbox'
                ];
            }
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idsolicitud',
            'label' => '#',
            'width' => '5%',
            'value' => function ($model) {
                return $model->idsolicitud;
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
            'visible' => false,
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'documento',
            'width' => '8%',
            'value' => function ($model) {
                $data = "";
                if ($model->documento) {
                    $data = "{$model->documento}";
                }
                return strtoupper($data);
            },
            'filterInputOptions' => ['placeholder' => 'Documento', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'apellido',
            'width' => '20%',
            'value' => function ($model) {
                $data = "";
                if ($model->apellido) {
                    $data = "{$model->apellido}";
                }
                return strtoupper($data);
            },
            'filterInputOptions' => ['placeholder' => 'Apellido', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'nombre',
            'width' => '20%',
            'value' => function ($model) {
                $data = "";
                if ($model->nombre) {
                    $data = "{$model->nombre}";
                }
                return strtoupper($data);
            },
            'filterInputOptions' => ['placeholder' => 'Nombre', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'legajo',
            'width' => '5%',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'categoria_actual',
            'width' => '2%',
            'value' => function ($model) {
                $data = "";
                $rhsur = $model->getConcRhSur();
                if ($rhsur) {
                    $data = $rhsur->categoria;
                }
                return $data;
            },
            'filterInputOptions' => ['placeholder' => 'Categoría', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'antiguedad',
            'width' => '10%',
            'value' => function ($model) {
                $data = "";
                $rhsur = $model->getConcRhSur();
                if ($rhsur) {
                    $data = "A: {$rhsur->antiguedad_administrativa} P: {$rhsur->antiguedad_privada} T: {$rhsur->antiguedad_total}";
                }
                return strtoupper($data);
            },
            'filterInputOptions' => ['placeholder' => 'Antigüedad', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'eventual',
            'width' => '4%',
            'value' => function ($model) {
                $data = "";
                $rhsur = $model->getConcRhSur();
                if ($rhsur) {
                    $data = $rhsur->eventual ? "Si" : "No";
                }
                return $data;
            },
            'filter' => ['1' => 'Si', '0' => 'No'],
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idusuario',
            'width' => '10%',
            'value' => function ($model) {
                $username = "";
                if ($model && isset($model->usuarioCarga)) {
                    $username = mb_strtoupper($model->usuarioCarga->apellido) . ", " . mb_strtoupper($model->usuarioCarga->nombre) . " (#{$model->usuarioCarga->idusuario})";
                }
                return $username;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $usuarioCargaFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Usuario...'],
            'format' => 'raw',
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
            'template' => "{view}{print}{postulacion}{addvacante}{delete}{reactivate}",
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
                'postulacion' => function () use ($permission) {
                    return ($permission['permissionRead']);
                },
                'addvacante' => function () use ($permission) {
                    return ($permission['permissionCreate']);
                },
            ],
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $url = Url::to(['mds_conc_solicitud/view', 'id' => $model->idsolicitud]);
                    return Html::a('<span class="fas fa-eye"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Ver',
                        'target' => '_blank',
                    ]);
                },
                'update' => function ($url, $model) {
                    $url = Url::to(['/mds_conc_solicitud/update', 'id' => $model->idsolicitud]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar',
                        'id' => 'bnt-update'
                    ]);
                },
                'delete' => function ($url, $model) {
                    $url =  Url::to(['/mds_conc_solicitud/delete', 'id' => $model->idsolicitud]);
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
                    $url =  Url::to(['/mds_conc_solicitud/reactivate', 'id' => $model->idsolicitud]);
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
                    $url =  Url::to(['/mds_conc_solicitud/reporte', 'ids' => $model->idsolicitud]);
                    return  Html::a('<span class="fas fa-print"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => ('Exportar PDF'),
                        'target' => '_blank',
                    ]);
                },
                'postulacion' => function ($url, $model) {
                    $url =  Url::to(['/mds_conc_postulacion/index', 'idsolicitud' => $model->idsolicitud]);
                    return  Html::a('<span class="fas fa-user"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => ('Ver postulaciones'),
                    ]);
                },
                'addvacante' => function ($url, $model, $key) {
                    $url = Url::to(['mds_conc_solicitud/add_vacante', 'id' => $model->idsolicitud]);
                    return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Agregar vacante',
                        'target' => '_blank',
                    ]);
                },
            ]
        ]
    ];
?>