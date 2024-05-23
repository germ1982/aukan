<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
        'attribute' => 'idregistro',
        'label' => '#',
        'width' => '4%',
        'value' => function ($model) {
            return $model['idregistro'];
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'numero_legajo_reproam',
        'width' => '6%',
        'label' => 'N° Legajo',
        'value' => function ($model) {
            return $model['numero_legajo_reproam'];
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'width' => '18%',
        'label' => 'Nombre',
        'value' => function ($model) {
            return $model['nombre'];
        }
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlocalidad',
        'label' => 'Localidad',
        'width' => '20%',
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
        'attribute' => 'idbarrio',
        'label' => 'Barrio',
        'width' => '18%',
        'value' => function ($model) {
            return $model->barrio['nombre'];
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $barriosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione Barrio...'],
        'format' => 'raw',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idzona',
        'label' => 'Zona',
        'width' => '15%',
        'value' => function ($model) {
            return $model->zona['descripcion'];
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
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'personeria_juridica',
        'label' => 'Personería Jurídica',
        'width' => '5%',
        'value' => function ($model) {
            if ($model->personeria_juridica == 1)
                return "Si";
            else
                return "No";
        },
        'filter' => ['0' => 'No', '1' => ' Si'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'inscripto',
        'label' => 'Inscripto',
        'width' => '5%',
        'value' => function ($model) {
            if ($model->inscripto == 1)
                return "Si";
            else
                return "No";
        },
        'filter' => ['0' => 'No', '1' => ' Si'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'width' => '8%',
        'visible' => ($hasRolAdminGeneral || $hasRolGlobal),
        'value' => function ($model) {
            if ($model->deleted_at === null)
                return "Si";
            else
                return "No";
        },
        'filter' => ['2' => 'Todos', '1' => 'Si', '0' => 'No'],
    ],
    // [
    //     'attribute' => 'personeria_juridica_fecha_vencimiento',
    //     'width' => '13.33%',
    //     'label' => 'Fecha Vencimiento Personeria Juridica',
    //     'value' => function ($model) {
    //             if ($model->personeria_juridica_fecha_vencimiento){
    //                 $date = date_create($model->personeria_juridica_fecha_vencimiento);
    //                 $date = date_format($date, 'd-m-Y');
    //                 return $date;
    //             }
    //     },
    //     'filter' => DatePicker::widget([
    //         'model' => $searchModel,
    //         'attribute' => 'personeria_juridica_fecha_vencimiento',
    //         'type' => DatePicker::TYPE_COMPONENT_APPEND ,
    //         'readonly' => true,
    //         'pluginOptions' => [
    //             'format' => 'dd-mm-yyyy',
    //             'autoclose' => true
    //         ]
    //     ])
    // ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $string,
        'width' => '5%',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'buttons' => [
            'update' => function ($url, $model, $key) use ($hasRolGlobal, $usuarioAuth, $hasRolAdminGeneral) {
                $idregistro = $model['idregistro'];
                $url = Url::to(['mds_reproam_registro/update', 'id' => $idregistro]);
                if ($hasRolAdminGeneral || $hasRolGlobal || ($model['idusuario_carga'] === $usuarioAuth->idusuario)) {
                    return Html::a('<span style="margin-left: 0.5rem" class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,  'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar'
                    ]);
                }
            },
            'print' => function ($url, $model) {
                $url =  Url::to(['/mds_reproam_registro/detalle_registro', 'idregistro' => $model->idregistro]);
                return  Html::a('<span style="margin-left: 0.5rem" class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF')
                ]);
            },
            'delete' => function ($url, $model, $key) use ($hasRolGlobal, $hasRolAdminGeneral, $usuarioAuth) {
                $url =  Url::to(['/mds_reproam_registro/delete', 'id' => $model->idregistro]);
                if (($hasRolGlobal || $hasRolAdminGeneral || ($model['idusuario_carga'] === $usuarioAuth->idusuario)) && !$model->deleted_at) {

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
            'reactivate' => function ($url, $model, $key) use ($hasRolGlobal, $hasRolAdminGeneral, $usuarioAuth) {
                if ($model['deleted_at'] && ($hasRolGlobal || $hasRolAdminGeneral)) {
                    $url =  Url::to(['/mds_reproam_registro/reactivate', 'id' => $model->idregistro]);
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
            // 'vermandatos' => function ($url, $model) {
            //     $url =  Url::to([
            //         '/mds_reproam_registro/ver_mandatos', 'idregistro' => $model->idregistro
            //     ]);
            //     return Html::a('<i style="margin-left: 0.5rem" class="far fa-eye"></i>', $url, [
            //         'role' => 'modal-remote',
            //         'title' => 'Ver mandatos',
            //         'data-toggle' => 'tooltip',
            //     ]);
            // },
        ]

    ],
];
