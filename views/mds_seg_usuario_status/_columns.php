<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
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
            'attribute' => 'idseg_usuario_status',
            'label' => '#',
            'width' => '5%',
            'value' => function ($model) {
                return $model->idseg_usuario_status;
            },
            'filterInputOptions' => ['class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idusuario',
            'width' => '10%',
            'value' => function ($model) {
                $username = "";
                if ($model && isset($model->usuario)) {
                    $username = mb_strtoupper($model->usuario->apellido) . ", " . mb_strtoupper($model->usuario->nombre) . " (#{$model->usuario->idusuario})";
                }
                return $username;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $usuarioFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Usuario...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idusuario_carga',
            'width' => '10%',
            'value' => function ($model) {
                $username = "";
                if ($model && isset($model->usuarioCarga)) {
                    $username = mb_strtoupper($model->usuarioCarga->apellido) . ", " . mb_strtoupper($model->usuarioCarga->nombre) . " (#{$model->usuarioCarga->idusuario})";
                }
                return $username;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $usuarioCargaFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Usuario...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idestado',
            'width' => '10%',
            'value' => function ($model) {
                $status = "";
                if ($model && isset($model->estado)) {
                    $status = $model->estado->descripcion;
                }
                return $status;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $statusFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Usuario...'],
            'format' => 'raw',
        ],
        [
            'attribute' => 'created_at',
            'width' => '10%',
            'value' => function ($model) {
                return $model->fechaCarga;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'created_at',
                'options' => ['placeholder' => 'Seleccione...'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'readonly' => true,
                'layout' => '{input}{remove}',
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose' => true
                ]
            ])
        ],

        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'template' => "{view}",
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'width' => '10%',
            'visibleButtons' => [
                'view'
            ],
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $url = Url::to(['mds_seg_usuario_status/view', 'id' => $model->idseg_usuario_status]);
                    return Html::a('<span class="fas fa-eye"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Ver',
                        'target' => '_blank',
                    ]);
                },
            ]
        ]
    ];
?>