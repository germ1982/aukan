<?php

use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

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
            'attribute' => 'idcertificacionprogramamonto',
            'width' => '5%',
            'value' => function ($model) {
                return $model['idcertificacionprogramamonto'];
            }
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'iddireccion',
            'width' => '22%',
            'value' => function ($model) {
                if ($model['iddireccion']) {
                    $data = strtoupper("{$model->direccion->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $direccionesFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ],
            ],
            'filterInputOptions' => [
                'placeholder' => 'Seleccione...',
                'allowClear' => true
            ],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idprograma',
            'width' => '20%',
            'value' => function ($model) {
                if ($model['idprograma']) {
                    $data = strtoupper("{$model->programa->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $programasFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true
                ],
            ],
            'filterInputOptions' => [
                'placeholder' => 'Seleccione...',
                'allowClear' => true
            ],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'monto',
            'width' => '15%',
            'value' => function ($model) {
                $data = "$ {$model->monto}";
                return $data;
            },
            'filterInputOptions' => ['placeholder' => 'Ingrese', 'class' => 'form-control'],
            'format' => 'raw'
        ],
        [
            'attribute' => 'fecha_inicio',
            'width' => '14%',
            'value' => function ($model) {
                $date = date_create($model->fecha_inicio);
                $date = date_format($date, 'd-m-Y');
                return $date;
            },
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_inicio',
                'options' => ['placeholder' => 'Inicio'],
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
            'attribute' => 'fecha_fin',
            'width' => '14%',
            'value' => function ($model) {
                if ($model->fecha_fin) {
                    $date = date_create($model->fecha_fin);
                    $date = date_format($date, 'd-m-Y');
                } else {
                    $date = "";
                }
                return $date;
            },
            'options' => ['readonly' => false],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_fin',
                'options' => ['placeholder' => 'Fin'],
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
            'class' => 'kartik\grid\ActionColumn',
            'width' => '10%',
            'dropdown' => false,
            'template' => $string,
            'vAlign' => 'middle',
            'hAlign' => 'center',
            // 'template' => '{view}',
            'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
            'buttons' =>
            [
                'update' => function ($url, $model) {
                    $url =  Url::to([
                        '/mds_certificacion_programa_monto/update', 'id' => $model->idcertificacionprogramamonto
                    ]);
                    return Html::a('  <i class="fas fa-pencil-alt" style="margin-left: 0.5rem"></i> ', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Editar',
                        'data-toggle' => 'tooltip',
                    ]);
                },
                'delete' => function ($url, $model, $key) use ($usuarioAuth) {
                    $url =  Url::to(['/mds_certificacion_programa_monto/delete', 'id' => $model->idcertificacionprogramamonto]);
                    return  Html::a(
                        '<span style="margin-left: 0.5rem" class="fas fa-trash"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Borrar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea eliminar el registro #' . $model->idcertificacionprogramamonto . '?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            ]
        ]
    ];
