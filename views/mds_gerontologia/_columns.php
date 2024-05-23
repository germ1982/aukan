<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$layoutDate = <<< HTML
    {input1}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idgerontologia',
        'label' => '#',
        'value' => function ($model) {
            return $model['idgerontologia'];
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpersona',
        'label' => 'Persona',
        'value' => function ($model) {
            $data = " {$model->persona['apellido']} {$model->persona['nombre']} ({$model->persona['documento']})";
            return $data;
        }
    ],
    [
        'attribute' => 'fecha_atencion',
        'value' => function ($model) {
            $date = date_create($model->fecha_atencion);
            $date = date_format($date, 'd-m-Y');
            return $date;
        },
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_atencion',
            'options' => ['placeholder' => 'Fecha de atencíon'],
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
        'attribute' => 'idvivienda',
        'label' => 'Vivienda',
        'value' => function ($model) {
            if ($model['idvivienda']) {
                $data = mb_strtoupper("{$model->vivienda->descripcion}");
            } else {
                $data = '';
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $viviendasFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo de vivienda'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'residencia',
        'label' => 'Residencia',
        'value' => function ($model) {
            $data = '';
            if ($model->residencia) {
                $data = $model->residencia;
            }
            return $data;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'width' => '8%',
        'value' => function ($model) {
            if ($model->deleted_at === null)
                return "Si";
            else
                return "No";
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
            'update' => function ($url, $model, $key) use ($hasRolAdmin, $hasRolAdminGeneral, $usuarioAuth) {
                $idgerontologia = $model['idgerontologia'];
                $url = Url::to(['mds_gerontologia/update', 'id' => $idgerontologia]);
                if ($hasRolAdmin || $hasRolAdminGeneral ||  ($model['idusuario_carga'] === $usuarioAuth->idusuario)) {
                    return Html::a('<span style="margin-left: 0.5rem" class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar'
                    ]);
                }
            },
            'print' => function ($url, $model) {
                $url =  Url::to(['/mds_gerontologia/detalle_gerontologia', 'id' => $model->idgerontologia]);
                return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF')
                ]);
            },
            'delete' => function ($url, $model, $key) use ($hasRolAdmin, $hasRolAdminGeneral, $usuarioAuth) {
                $url =  Url::to(['/mds_gerontologia/delete', 'id' => $model->idgerontologia]);
                if (($hasRolAdmin || $hasRolAdminGeneral || (($model['idusuario_carga'] === $usuarioAuth->idusuario))) && !$model->deleted_at) {

                    return  Html::a(
                        '<span style="margin-left: 0.5rem" class="fas fa-trash"></span>',
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
                if ($model['deleted_at'] && $hasRolAdminGeneral) {
                    $url =  Url::to(['/mds_gerontologia/reactivate', 'id' => $model->idgerontologia]);
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
