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
            'attribute' => 'idcertificaciondirector',
            'label' => '#',
            'width' => '4%',
            'value' => function ($model) {
                return $model->idcertificaciondirector;
            },
            'filter'=>false
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idusuario',
            'label' => 'Usuario',
            'value' => function ($model) {
                return strtoupper("{$model->usuario->apellido} {$model->usuario->nombre} {$model->usuario->dni}");
            },
            'filter' => false,
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idfuncion',
            'label' => 'Función que desempeña',
            'value' => function ($model) {
                return $model->idfuncion ? strtoupper("{$model->funcion_usuario->descripcion}") : '';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filterFunciones,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
            'filter'=>false
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
            'attribute' => 'observaciones',
            'label' => 'Observaciones',
            'value' => function ($model) {
                return strtoupper("{$model->observaciones}");
            },
            'filter' => false,
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
            'filter'=>false
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
                    $url =  Url::to(['/mds_certificacion_director/update', 'id' =>  $model->idcertificaciondirector]);
                    return Html::a('  <i class="fas fa-pencil-alt" style="margin-left: 0.5rem"></i> ', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Editar',
                        'data-toggle' => 'tooltip',
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    return
                        Html::a(
                            '<span class="fas fa-trash" style="margin-left: 0.5rem"></span>',
                            ['delete', 'idcertificaciondirector' => $model->idcertificaciondirector],
                            [
                                'role' => 'modal-remote',
                                'title' => 'Borrar',
                            ]
                        );
                },
                'reactivate' => function ($url, $model, $key) {
                    return
                        Html::a(
                            '<span class="fas fa-check" style="margin-left: 0.5rem"></span>',
                            ['reactivate', 'idcertificaciondirector' => $model->idcertificaciondirector],
                            [
                                'role' => 'modal-remote',
                                'title' => 'Reactivar',
                            ]
                        );
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
                    return ($model->deleted_at && $hasRolAdminGeneral);
                },
            ],
        ],
    ];
