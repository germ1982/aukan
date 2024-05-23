<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;

$layoutDate = <<< HTML
    {input1}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return
    [
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idcertificaciondireccion',
            'label' => '#',
            'value' => function ($model) {
                return $model['idcertificaciondireccion'];
            }
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'iddireccion',
            'label' => 'Dirección/Área',
            'value' => function ($model) {
                if ($model['iddireccion']) {
                    $data = strtoupper("{$model->direccion0->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filterDirecciones,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'iddireccion_padre',
            'label' => 'Dependiente de',
            'value' => function ($model) {
                if ($model['iddireccion_padre']) {
                    $data = strtoupper("{$model->direccionPadre->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filterDireccionesDependiente,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idnivelautorizacion',
            'label' => 'Nivel de autorización',
            'value' => function ($model) {
                if ($model['idnivelautorizacion']) {
                    $data = strtoupper("{$model->nivelAutorizacion0->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filterNivelAutorizacion,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'usuario',
            'label' => 'A cargo de',
            'value' => function ($model) {
                $data = "";
                if ($model->usuario) {
                    $data = "{$model->director->apellido} {$model->director->nombre}";
                }
                return $data;
            },
            'filterInputOptions' => ['placeholder' => 'Ingrese', 'class' => 'form-control']
        ],
        [
            'attribute' => 'fecha_desde',
            'value' => function ($model) {
                if ($model->fecha_desde) {
                    $date = date_create($model->fecha_desde);
                    $date = date_format($date, 'd-m-Y');
                } else {
                    $date = "";
                }
                return $date;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_desde',
                'options' => ['placeholder' => 'Desde'],
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
            'attribute' => 'fecha_hasta',
            'value' => function ($model) {
                if ($model->fecha_hasta) {
                    $date = date_create($model->fecha_hasta);
                    $date = date_format($date, 'd-m-Y');
                } else {
                    $date = "";
                }
                return $date;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_hasta',
                'options' => ['placeholder' => 'Hasta'],
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
                return $model->deleted_at === NULL ? 'Si' : 'No';
            },
            'filter' => ['1' => 'Si', '0' => 'No'],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'template' => $string,
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
            'buttons' =>
            [
                'update' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion_direccion/update', 'id' =>  $model->idcertificaciondireccion]);
                    return Html::a('  <i class="fas fa-pencil-alt" style="margin-left: 0.5rem"></i> ', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Editar',
                        'data-toggle' => 'tooltip',
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    $url =  Url::to(['/mds_certificacion_direccion/delete', 'id' => $model->idcertificaciondireccion]);
                    return  Html::a('<span class="fas fa-trash" style="margin-left: 0.5rem"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Borrar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea eliminar el registro #' . $model->idcertificaciondireccion . '?',
                            'method' => 'post',
                        ],
                    ]);
                },
                'reactivate' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion_direccion/reactivate',  'id' => $model->idcertificaciondireccion]);
                    return  Html::a('<span class="fas fa-check" style="margin-left: 0.5rem"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Re-activar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea re-activar este elemento?',
                            'method' => 'post',
                        ],
                    ]);
                },
                'usuarios' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion_director/index', 'idcertificaciondireccion' => $model->idcertificaciondireccion]);
                    return Html::a('<i class="fas fa-child" style="margin-left: 0.5rem"></i>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Usuarios',
                        'data-toggle' => 'tooltip',
                    ]);
                }
            ],
            'visibleButtons' => [
                'update' => function ($model) use ($hasRolAdminGeneral) {
                    return (is_null($model->deleted_at) && $hasRolAdminGeneral);
                },
                'delete' => function ($model) use ($hasRolAdminGeneral) {
                    return (is_null($model->deleted_at) && $hasRolAdminGeneral);
                },
                'reactivate' => function ($model) use ($hasRolAdminGeneral) {
                    return (!is_null($model->deleted_at) && $hasRolAdminGeneral);
                },
            ],
        ],
    ];
