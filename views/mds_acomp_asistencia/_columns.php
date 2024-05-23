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
        'attribute' => 'idasistencia',
        'label' => '#',
        'width' => '4%',
        'value' => function ($model) {
            return $model['idasistencia'];
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idbeneficiario',
        'width' => '13%',
        'label' => 'Beneficiario',
        'value' => function ($model) {
            $apellido = mb_strtoupper($model->beneficiario['apellido']);
            $nombre = mb_strtoupper($model->beneficiario['nombre']);
            $dni = $model->beneficiario['documento'];
            $data = "$apellido, $nombre ($dni)";
            return $data;
        }
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlocalidad',
        'label' => 'Localidad',
        'width' => '15%',
        'value' => function ($model) {
            return $model->localidad['descripcion'];
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
        'attribute' => 'idlocalidad_ingreso',
        'label' => 'Localidad de ingreso',
        'width' => '15%',
        'value' => function ($model) {
            return $model->localidadIngreso['descripcion'];
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $localidadesIngresoFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione Localidad...'],
        'format' => 'raw',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idriesgo',
        'label' => 'Riesgo',
        'width' => '8%',
        'value' => function ($model) {
            return $model->riesgo['descripcion'];
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ['Altisimo' => 'Altisimo', 'Alto' => 'Alto', 'Medio' => 'Medio', 'Bajo' => 'Bajo'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione Riesgo...'],
        'format' => 'raw',
    ],

    [
        'attribute' => 'periodo_desde',
        'width' => '19%',
        'value' => function ($model) {
            $date = date_create($model->periodo_desde);
            $date = date_format($date, 'd-m-Y');
            return $date;
        },
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'periodo_desde',
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
        'attribute' => 'periodo_hasta',
        'width' => '19%',
        'value' => function ($model) {
            if ($model->periodo_hasta) {
                $date = date_create($model->periodo_hasta);
                $date = date_format($date, 'd-m-Y');
            } else {
                $date = "";
            }
            return $date;
        },
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'id' => 'fechaHastaId',
            'attribute' => 'periodo_hasta',
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
        'width' => '7%',
        'visible' => ($hasRolAdminGeneral || $hasRolGlobal),
        'value' => function ($model) {
            if ($model->deleted_at === null)
                return "Si";
            else
                return "No";
        },
        'filter' => ['0' => 'No', '1' => 'Si'],
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $string,
        'width' => '5%',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'buttons' =>
        [
            'update' => function ($url, $model, $key) use ($hasRolGlobal, $usuarioAuth, $hasRolAdminGeneral) {
                $idasistencia = $model['idasistencia'];
                $url = Url::to(['mds_acomp_asistencia/update', 'id' => $idasistencia]);
                if ($hasRolAdminGeneral || $hasRolGlobal || ($model['idusuario_carga'] === $usuarioAuth->idusuario)) {
                    return Html::a('<span style="margin-left: 0.5rem" class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,  'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar'
                    ]);
                }
            },
            'print' => function ($url, $model) {
                $url =  Url::to(['/mds_acomp_asistencia/detalle_asistencia', 'id' => $model->idasistencia]);
                return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF')
                ]);
            },
            'delete' => function ($url, $model, $key) use ($hasRolGlobal, $usuarioAuth, $hasRolAdminGeneral) {
                $url =  Url::to(['/mds_acomp_asistencia/delete', 'id' => $model->idasistencia]);
                if (($hasRolAdminGeneral || $hasRolGlobal || ($model['idusuario_carga'] === $usuarioAuth->idusuario)) && !$model->deleted_at) {

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
            'reactivate' => function ($url, $model, $key) use ($hasRolGlobal, $hasRolAdminGeneral) {
                if ($model['deleted_at'] && ($hasRolGlobal || $hasRolAdminGeneral)) {
                    $url =  Url::to(['/mds_acomp_asistencia/reactivate', 'id' => $model->idasistencia]);
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
