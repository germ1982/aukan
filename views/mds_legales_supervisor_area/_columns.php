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
        'attribute' => 'idlegalessupervisorarea',
        'label' => '#',
        'value' => function ($model) {
            return $model['idlegalessupervisorarea'];
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarea',
        'value' => function ($model) {
            return $model->area['descripcion'];
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filterAreas,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione área...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',

        'value' => function ($model) {
            $apellido = mb_strtoupper($model->usuario->apellido);
            $nombre = mb_strtoupper($model->usuario->nombre);
            return "$apellido, $nombre";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filterUsuarios,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione usuario...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'visible' => ($hasRolAdminGeneral),
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
            'delete' => function ($url, $model, $key) use ($hasRolAdminGeneral, $usuarioAuth) {
                $url =  Url::to(['/mds_legales_supervisor_area/delete', 'id' => $model->idlegalessupervisorarea]);
                if (($hasRolAdminGeneral || ($model['idusuario_alta'] === $usuarioAuth->idusuario)) && !$model->deleted_at) {
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
            'reactivate' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                if ($model['deleted_at'] && ($hasRolAdminGeneral)) {
                    $url =  Url::to(['/mds_legales_supervisor_area/reactivate', 'id' => $model->idlegalessupervisorarea]);
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
