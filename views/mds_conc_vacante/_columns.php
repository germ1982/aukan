<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

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
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idvacante',
            'label' => '#',
            'width' => '4%',
            'value' => function ($model) {
                return $model->idvacante;
            },
            'filterInputOptions' => ['class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idconcurso',
            'width' => '17%',
            'value' => function ($model) {
                return ($model->concurso) ? $model->concurso->descripcion : '';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $concursosFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'multiple' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'categoria',
            'width' => '7%',
            'value' => function ($model) {
                return ($model->categoria0) ? $model->categoria0->descripcion : '';
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $categoriasFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'multiple' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'requiere_titulo',
            'width' => '6%',
            'visible' => $hasRolAdminGeneral,
            'value' => function ($model) {
                return ($model->requiere_titulo) ? 'Si' : 'No';
            },
            'filter' => ['1' => 'Si', '0' => 'No'],
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'cantidad',
            'width' => '4%',
            'value' => function ($model) {
                $data = "";
                if ($model->cantidad) {
                    $data = "{$model->cantidad}";
                }
                return $data;
            },
            'filterInputOptions' => ['placeholder' => 'Cantidad', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'Postulaciones',
            'width' => '4%',
            'value' => function ($model) {
                $data = "";
                if ($model->postulaciones) {
                    $data = count($model->postulaciones);
                }
                return $data;
            },
            'filterInputOptions' => ['placeholder' => 'Postulaciones', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'deleted_at',
            'width' => '6%',
            'visible' => $hasRolAdminGeneral,
            'value' => function ($model) {
                return is_null($model->deleted_at) ? 'Si' : 'No';
            },
            'filter' => ['1' => 'Si', '0' => 'No'],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'template' => "{view}{update}{print}{delete}{reactivate}{postulantes}",
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'width' => '9%',
            'visibleButtons' => [
                'view' => function () use ($permission) {
                    return ($permission['permissionRead']);
                },
                'update' => function () use ($permission) {
                    return ($permission['permissionUpdate']);
                },
                'print' => function () use ($permission) {
                    return ($permission['permissionRead']);
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
                    $url = Url::to(['mds_conc_vacante/view', 'id' => $model->idvacante]);
                    return Html::a('<span class="fas fa-eye"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Ver',
                        'target' => '_blank',
                    ]);
                },
                'update' => function ($url, $model) {
                    $url = Url::to(['/mds_conc_vacante/update', 'id' => $model->idvacante]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar',
                        'id' => 'bnt-update'
                    ]);
                },
                'postulantes' => function ($url, $model) {
                    $url = Url::to(['/mds_conc_vacante/postulantes', 'id' => $model->idvacante]);
                    return Html::a('<span class="fas fa-users"></span>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Ver postulantes',
                        'data-toggle' => 'tooltip'
                    ]);
                },
                'delete' => function ($url, $model) {
                    $url =  Url::to(['/mds_conc_vacante/delete', 'id' => $model->idvacante]);
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
                    $url =  Url::to(['/mds_conc_vacante/reactivate', 'id' => $model->idvacante]);
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
                'print' => function ($url, $model) {
                    $url =  Url::to(['/mds_conc_vacante/reporte', 'id' => $model->idvacante]);
                    return  Html::a('<span class="fas fa-print"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => ('Exportar PDF'),
                        'target' => '_blank',
                    ]);
                },
            ]
        ]
    ];
?>