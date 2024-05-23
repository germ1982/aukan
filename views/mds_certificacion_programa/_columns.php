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
            'attribute' => 'idcertificacionprograma',
            'label' => '#',
            'width' => '5%',
            'value' => function ($model) {
                return $model['idcertificacionprograma'];
            }
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idprograma',
            'label' => 'Programa',
            'width' => '30%',
            'value' => function ($model) {
                if ($model['idprograma']) {
                    $data = strtoupper("{$model->programa0->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filterProgramas,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idcertificaciondireccion',
            'label' => 'Dirección/Área',
            'width' => '20%',
            'value' => function ($model) {
                if ($model['idcertificaciondireccion']) {
                    $data = strtoupper("{$model->direccion0->direccion0->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filterDirecciones,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'monto',
            'width' => '10%',
            'value' => function ($model) {
                $data = '';
                if ($model->monto) {
                    $data = "$ {$model->monto}";
                }
                return $data;
            },
            'filterInputOptions' => ['placeholder' => 'Ingrese', 'class' => 'form-control'],
            'format' => 'raw'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'cambio_responsable',
            'label' => 'Permite cambio de responsable',
            'width' => '10%',
            'value' => function ($model) {
                if ($model->cambio_responsable === 1)
                    return "Si";
                else
                    return "No";
            },
            'filter' => ['0' => 'No', '1' => 'Si']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'requiere_autorizacion',
            'label' => 'Requiere autorización previa',
            'width' => '10%',
            'value' => function ($model) {
                if ($model->requiere_autorizacion === 1)
                    return "Si";
                else
                    return "No";
            },
            'filter' => ['0' => 'No', '1' => 'Si']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idtipo_subsidio',
            'label' => 'Tipo Subsidio',
            'width' => '10%',
            'value' => function ($model) {
                if ($model['idtipo_subsidio']) {
                    $data = strtoupper("{$model->tipoSubsidio0->descripcion}");
                } else {
                    $data = '';
                }
                return $data;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $filterTipoSubsidio,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'deleted_at',
            'width' => '4%',
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
            'width' => '5%',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
            'buttons' =>
            [
                'update' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion_programa/update', 'id' => $model->idcertificacionprograma]);
                    return Html::a('  <i class="fas fa-pencil-alt" style="margin-left: 0.5rem"></i> ', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Editar',
                        'data-toggle' => 'tooltip',
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    $url =  Url::to(['/mds_certificacion_programa/delete', 'id' => $model->idcertificacionprograma]);
                    return  Html::a('<span class="fas fa-trash" style="margin-left: 0.5rem"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Borrar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea eliminar el registro #' . $model->idcertificacionprograma . '?',
                            'method' => 'post',
                        ],
                    ]);
                },
                'reactivate' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion_programa/reactivate',  'id' => $model->idcertificacionprograma]);
                    return  Html::a('<span class= "fas fa-check" style="margin-left:0.5rem"></span>', $url, [
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
            ],
        ],
    ];
