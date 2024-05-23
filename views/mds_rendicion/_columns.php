<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use app\models\Mds_rendicion;

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
            'headerOptions' => ['style' => 'width:2%'],
            'checkboxOptions' =>
            function ($model) {
                return [
                    'value' => $model->idrendicion,
                    'class' => 'checkbox-row', 'id' => 'checkbox_' . $model->idrendicion
                ];
            }
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idrendicion',
            'label' => '#',
            'width' => '5%',
            'value' => function ($model) {
                return $model->idrendicion;
            },
            'filterInputOptions' => ['class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idtipo',
            'width' => '10%',
            'value' => function ($model) {
                return $model->tipo->descripcion;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $listTipos,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
        ],

        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'monto',
            'width' => '10%',
            'value' => function ($model) {
                $data = "$ {$model->monto}";
                return $data;
            },
            'filterInputOptions' => ['placeholder' => 'Monto', 'class' => 'form-control']
        ],

        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'sujeto',
            'width' => '18%',
            'value' => function ($model) {
                $data = "";
                if ($model->persona) {
                    $data = " {$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})";
                }
                if ($model->usuarioComprobante) {
                    $data = " {$model->usuarioComprobante->apellido} {$model->usuarioComprobante->nombre} ({$model->usuarioComprobante->dni})";
                }
                return strtoupper($data);
            },
            'filterInputOptions' => ['placeholder' => 'Ingrese', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idlugar',
            'width' => '10%',
            'value' => function ($model) {
                return ($model->lugar) ? $model->lugar->descripcion : '';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filtroLugar,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
        ],
        [
            'attribute' => 'fecha_comprobante',
            'width' => '10%',
            'value' => function ($model) {
                return $model->fechaComprobante;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_comprobante',
                'options' => ['placeholder' => 'Seleccione...'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'readonly' => true,
                'layout' => '{input}{remove}',
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'autoclose' => true
                ]
            ])
        ],
        [
            'attribute' => 'fecha_vale',
            'width' => '10%',
            'value' => function ($model) {
                return  $model->fechaVale;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'fecha_vale',
                'options' => ['placeholder' => 'Seleccione...'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'readonly' => true,
                'layout' => '{input}{remove}',
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'autoclose' => true
                ]
            ])
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idusuario_carga',
            'width' => '10%',
            'value' => function ($model) {
                $username = "";
                if ($model && isset($model->usuarioCarga)) {
                    $username = mb_strtoupper($model->usuarioCarga->apellido) . ", " . mb_strtoupper($model->usuarioCarga->nombre);
                }
                return $username;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filtroUsuarioCarga,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Usuario...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'deleted_at',
            'width' => '5%',
            'visible' => $hasRolAdminGeneral,
            'value' => function ($model) {
                return is_null($model->deleted_at) ? 'Si' : 'No';
            },
            'filter' => ['1' => 'Si', '0' => 'No'],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'template' => '{view}{update}{comprobante}{print}{delete}{reactivate}',
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
                'comprobante' => function ($model) use ($permission) {
                    return ($permission['permissionUpdate'] && $model->idtipo == Mds_rendicion::TIPO_COMBUSTIBLE);
                },
                'print' => function () use ($permission) {
                    return ($permission['permissionPrint']);
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
                    $url = Url::to(['mds_rendicion/view', 'id' => $model->idrendicion]);
                    return Html::a('<span class="fas fa-eye"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Ver',
                        'target' => '_blank',
                    ]);
                },
                'update' => function ($url, $model) {
                    $url = Url::to(['/mds_rendicion/update', 'id' => $model->idrendicion]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar',
                        'id' => 'bnt-update'
                    ]);
                },
                'comprobante' => function ($url, $model) {
                    return Html::button('<span class="fas fa-receipt" style="margin-left: 0.5rem"></span>', [
                        'type' => "button",
                        'class' => 'btn-rendicion-comprobante',
                        'data-toggle' => "modal",
                        'data-target' => "#modalRendicionComprobante",
                        'title' => 'Presentar Comprobante',
                        'data-idrendicion' => $model->idrendicion,
                    ]);
                },
                'print' => function ($url, $model) {
                    $url =  Url::to(['/mds_rendicion/reporte', 'id' => $model->idrendicion]);
                    return  Html::a('<span class="fas fa-print"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => ('Exportar PDF'),
                        'target' => '_blank',
                    ]);
                },
                'delete' => function ($url, $model) {
                    $url =  Url::to(['/mds_rendicion/delete', 'id' => $model->idrendicion]);
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
                    $url =  Url::to(['/mds_rendicion/reactivate', 'id' => $model->idrendicion]);
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