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
return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idmandato',
        'label' => '#',
        'value' => function ($model) {
            return $model['idmandato'];
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idregistro',

        'value' => function ($model) {
            return $model->registro->nombre;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $registroFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione Registro...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlocalidad',
        'label' => 'Localidad',
        'width' => '20%',
        'value' => function ($model) {
            return $model->registro->localidad['descripcion'];
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $localidadesFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione Localidad...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idzona',
        'label' => 'Zona',
        'width' => '15%',
        'value' => function ($model) {
            return $model->registro->zona['descripcion'];
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $zonasFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione Zona...'],
        'format' => 'raw',
    ],
    [
        'attribute' => 'fecha_desde',
        'label' => 'Fecha Desde',
        'value' => function ($model) {
            $date = date_create($model->fecha_desde);
            $date = date_format($date, 'd-m-Y');
            return $date;
        },
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_desde',
            'options' => ['placeholder' => 'Desde'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
    [
        'attribute' => 'fecha_hasta',
        'label' => 'Fecha Hasta',
        'value' => function ($model) {
            if ($model->fecha_hasta) {
                $date = date_create($model->fecha_hasta);
                $date = date_format($date, 'd-m-Y');
            } else {
                $date = "";
            }
            return $date;
        },
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'id' => 'fechaHastaId',
            'attribute' => 'fecha_hasta',
            'options' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'separator' => ' ',
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'visible' => ($hasRolAdminGeneral || $hasRolGlobal),
        'value' => function ($model) {
            if ($model->deleted_at === null)
                return "Si";
            else
                return "No";
        },
        'filter' => ['2' => 'Todos', '1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $string,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['data-pjax' => 0, 'role' => 'post', 'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'style' => 'margin-left: 0.5rem'],
        'buttons' => [
            'delete' => function ($url, $model, $key) use ($hasRolGlobal, $hasRolAdminGeneral, $usuarioAuth) {
                $url =  Url::to(['/mds_reproam_mandato/delete', 'id' => $model->idmandato]);
                if (($hasRolGlobal || $hasRolAdminGeneral || ($model['idusuario_carga'] === $usuarioAuth->idusuario)) && !$model->deleted_at) {
                    return  Html::a(
                        '<span style="margin-left: 0.5rem" class= "fas fa-trash"></span>',
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
            'reactivate' => function ($url, $model, $key) use ($hasRolGlobal, $hasRolAdminGeneral, $usuarioAuth) {
                if ($model['deleted_at'] && ($hasRolGlobal || $hasRolAdminGeneral)) {
                    $url =  Url::to(['/mds_reproam_mandato/reactivate', 'id' => $model->idmandato]);
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
