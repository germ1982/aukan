<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use app\models\Mds_conc_solicitud;
?>

<style>
    td>a>span {
        margin-left: 0.5rem;
    }
</style>

<?php
return [
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '3%',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
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
        'attribute' => 'idsolicitud',
        'width' => '4%',
        'value' => function ($model) {
            return $model->idsolicitud;
        },
        'filterInputOptions' => ['class' => 'form-control']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
        'width' => '8%',
        'value' => function ($model) {
            $data = "";
            if ($model->solicitud->documento) {
                $data = "{$model->solicitud->documento}";
            }
            return strtoupper($data);
        },
        'filterInputOptions' => ['placeholder' => 'Documento', 'class' => 'form-control']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
        'width' => '10%',
        'value' => function ($model) {
            $data = "";
            if ($model->solicitud->apellido) {
                $data = "{$model->solicitud->apellido}";
            }
            return strtoupper($data);
        },
        'filterInputOptions' => ['placeholder' => 'Apellido', 'class' => 'form-control']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'width' => '10%',
        'value' => function ($model) {
            $data = "";
            if ($model->solicitud->nombre) {
                $data = "{$model->solicitud->nombre}";
            }
            return strtoupper($data);
        },
        'filterInputOptions' => ['placeholder' => 'Nombre', 'class' => 'form-control']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'legajo',
        'width' => '10%',
        'value' => function ($model) {
            $data = "";
            if ($model->solicitud->legajo) {
                $data = "{$model->solicitud->legajo}";
            }
            return strtoupper($data);
        },
        'filterInputOptions' => ['placeholder' => 'Legajo', 'class' => 'form-control']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'categoria_actual',
        'width' => '2%',
        'value' => function ($model) {
            $data = "";
            $rhsur = $model->solicitud->getConcRhSur();
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
            $rhsur = $model->solicitud->getConcRhSur();
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
            $rhsur = $model->solicitud->getConcRhSur();
            if ($rhsur) {
                $data = $rhsur->eventual ? "Si" : "No";
            }
            return $data;
        },
        'filter' => ['1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mail',
        'width' => '10%',
        'value' => function ($model) {
            $data = "";
            if ($model->solicitud->legajo) {
                $data = "{$model->solicitud->mail}";
            }
            return strtoupper($data);
        },
        'filterInputOptions' => ['class' => 'form-control'],
        'hidden' => true
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
        'width' => '10%',
        'value' => function ($model) {
            $data = "";
            if ($model->solicitud->telefono) {
                $data = "{$model->solicitud->telefono}";
            }
            return strtoupper($data);
        },
        'filterInputOptions' => ['class' => 'form-control'],
        'hidden' => true
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'domicilio_fiscal',
        'width' => '10%',
        'value' => function ($model) {
            $data = "";
            if ($model->solicitud->domicilio_fiscal) {
                $data = "{$model->solicitud->domicilio_fiscal}";
            }
            return strtoupper($data);
        },
        'filterInputOptions' => ['class' => 'form-control'],
        'hidden' => true
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idconcurso',
        'width' => '10%',
        'value' => function ($model) {
            return ($model->solicitud->concurso) ? $model->solicitud->concurso->descripcion : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $concursosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
        'hidden' => true,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idvacante',
        'width' => '2%',
        'value' => function ($model) {
            if ($model->vacante) {
                $categoria = $model->vacante->categoria0->descripcion;
                $vacanteDetalle = "$categoria";
            }
            return ($model->vacante) ? $vacanteDetalle : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $vacantesFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width' => '10%',
        'value' => function ($model) {
            $data = "";
            if ($model->estado0) {
                $data = "{$model->estado0->descripcion}";
            }
            return strtoupper($data);
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $estadosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'puntaje',
        'width' => '5%',
        'value' => function ($model) {
            $data = "";
            if (($model->estado === Mds_conc_solicitud::ESTADO_SELECCIONADO || $model->estado === Mds_conc_solicitud::ESTADO_ADMITIDO) && $model->puntaje) {
                $data = "{$model->puntaje}";
            }
            return strtoupper($data);
        },
        'filterInputOptions' => ['placeholder' => 'Puntaje', 'class' => 'form-control']
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
        'width' => '6%',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            return is_null($model->deleted_at) ? 'Si' : 'No';
        },
        'filter' => ['1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => '6%',
        'dropdown' => false,
        'template' => "{solicitud}{estado}{historialestado}{impugnacion}{delete}{reactivate}",
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'visibleButtons' => [
            'view' => function () use ($permission) {
                return ($permission['permissionRead']);
            },
            'solicitud' => function () use ($permission) {
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
                $url = Url::to(['mds_conc_postulacion/view', 'id' => $model->idpostulacion]);
                return Html::a('<span class="fas fa-eye"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Ver',
                    'target' => '_blank',
                ]);
            },
            'solicitud' => function ($url, $model, $key) {
                $url = Url::to(['mds_conc_solicitud/view', 'id' => $model->idsolicitud]);
                return Html::a('<span class="fas fa-eye"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Ver solicitud',
                    'target' => '_blank',
                ]);
            },
            'update' => function ($url, $model) {
                $url = Url::to(['/mds_conc_postulacion/update', 'id' => $model->idpostulacion]);
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Actualizar',
                    'id' => 'bnt-update'
                ]);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/mds_conc_postulacion/delete', 'id' => $model->idpostulacion]);
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
                $url =  Url::to(['/mds_conc_postulacion/reactivate', 'id' => $model->idpostulacion]);
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
                $url =  Url::to(['/mds_conc_postulacion/reporte', 'id' => $model->idpostulacion]);
                return  Html::a('<span style="margin-left:0.5rem" class="fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF'),
                    'target' => '_blank',
                ]);
            },
            'estado' => function ($url, $model) {
                // Si el estado es rechazado o reasignado, no se peude realizar ningun cambio de estado
                if ($model->estado != Mds_conc_solicitud::ESTADO_RECHAZADO && $model->estado != Mds_conc_solicitud::ESTADO_REASIGNADO) {
                    $url =  Url::to(['/mds_conc_historial/create', 'idpostulacion' => $model->idpostulacion]);
                    return Html::a('<span class="fab fa-ioxhost"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Cambiar estado de la postulacion',
                    ]);
                }
            },
            'historialestado' => function ($url, $model) {
                $url =  Url::to(['/mds_conc_historial/index', 'idpostulacion' => $model->idpostulacion]);
                return Html::a('<span class="far fa-clipboard"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Historial de estados',
                ]);
            },
            'impugnacion' => function ($url, $model) {
                if ($model->impugnacion) {
                    $url =  Url::to(['/mds_conc_postulacion/impugnacion', 'idpostulacion' => $model->idpostulacion]);
                    return Html::a('<span class="far fa-file"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Ver impugnación',
                    ]);
                }
            }
        ]
    ],

];
