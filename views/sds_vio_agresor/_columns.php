<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\Html;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idagresor',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'genero',
        'label' => 'Género',
        'width' => '18%',
        'value' => function ($model) {
            return ($model->genero0 ? $model->genero0['descripcion'] :  "");
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $generosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione Género ...'],
        'format' => 'raw',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'label' => 'Activo',
        'width' => '8%',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            if ($model->activo == 1)
                return "Si";
            else
                return "No";
        },
        'filter' => ['0' => 'No', '1' => 'Si'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {delete} {intervenciones_asociadas} {reactivate}',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip', 'style' => 'margin-left: 0.5rem'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-pjax' => 1,
            'title' => 'Borrar',
            'data' => [
                'confirm' => '¿Está seguro que desea eliminar este elemento?',
                'method' => 'post',
            ],
            'style' => 'margin-left: 0.5rem'
            // 'hidden' => ($model->activo == 1)? false : true,
        ],

        'visibleButtons' => [
            'delete' => function ($model) {
                return $model->activo == 1;
            },
            'update' => function ($model) {
                return $model->activo == 1;
            },
            'reactivate' => function ($model) use ($hasRolAdminGeneral) {
                return ($hasRolAdminGeneral && $model->activo === 0);
            }
        ],

        'buttons' => [
            'intervenciones_asociadas' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/intervenciones_asociadas', 'idagresor' => $model->idagresor]);
                return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-list"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Ver intervenciones',
                    'data-toggle' => 'tooltip'
                ]);
            },
            'reactivate' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/reactivate', 'id' => $model->idagresor]);
                return  Html::a(
                    '<span style="margin-left:0.5rem" class= "fas fa-check"></span>',
                    $url,
                    [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Re-activar'),
                        'data-request-method' => 'post',
                        'data-pjax' => 1,
                        'data' => [
                            'confirm' => '¿Está seguro que desea re-activar este elemento?',
                            'method' => 'post',
                        ],
                    ]
                );
            }
        ]
    ],
];
