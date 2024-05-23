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
return
    [
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'iddireccionusuario',
            'label' => '#',
            'value' => function ($model) {
                return $model['iddireccionusuario'];
            },
            'width' => '10%',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idcertificaciondireccion',
            'label' => 'Dirección/Área',
            'value' => function ($model) {
                if ($model['idcertificaciondireccion']) {
                    $data = strtoupper("{$model->idcertificaciondireccion0->direccion0->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'width' => '35%',
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
            'attribute' => 'idusuario',
            'label' => 'Usuario',
            'value' => function ($model) {
                if ($model['idusuario']) {
                    $data = strtoupper("{$model->usuario->apellido} {$model->usuario->nombre}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'width' => '35%',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filterUsuarios,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'deleted_at',
            'width' => '8%',
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
            'width' => '12%',
            'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
            'buttons' =>
            [
                'update' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion_direccion_usuario/update', 'id' =>  $model->iddireccionusuario]);
                    return Html::a('  <i class="fas fa-pencil-alt" style="margin-left: 0.5rem"></i> ', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Editar',
                        'data-toggle' => 'tooltip',
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    $url =  Url::to(['/mds_certificacion_direccion_usuario/delete', 'id' => $model->iddireccionusuario]);
                    return  Html::a('<span class="fas fa-trash" style="margin-left: 0.5rem"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Borrar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea eliminar el registro #' . $model->iddireccionusuario . '?',
                            'method' => 'post',
                        ],
                    ]);
                },
                'reactivate' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion_direccion_usuario/reactivate',  'id' => $model->iddireccionusuario]);
                    return  Html::a('<span class="fas fa-check" style="margin-left:0.5rem"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Re-activar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea re-activar este elemento?',
                            'method' => 'post',
                        ],
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
            ]
        ]
    ];
