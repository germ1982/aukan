<?php

use kartik\helpers\Html;
use yii\helpers\Url;

$columna1 = "60%";
$columna2 = "10%";
$columna3 = "10%";
$columna4 = "10%";
$columna5 = "10%";
return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'width' => $columna1,
    ],
    [
        'attribute' => 'periodo_inicio',
        'format' => 'raw',
        'width' => $columna2,
        'value' => function ($model) {
            return $model->periodo_inicio
                ? date('d/m/Y', strtotime($model->periodo_inicio))
                : '<span class="text-muted">-</span>';
        }
    ],
    [
        'attribute' => 'periodo_final',
        'format' => 'raw',
        'width' => $columna3,
        'value' => function ($model) {
            return $model->periodo_final
                ? date('d/m/Y', strtotime($model->periodo_final))
                : '<span class="text-muted">-</span>';
        }
    ],
    [
        'attribute' => 'periodo_prorroga',
        'format' => 'raw',
        'width' => $columna4,
        'value' => function ($model) {
            return $model->periodo_prorroga
                ? '<span class="badge badge-info">' . date('d/m/Y', strtotime($model->periodo_prorroga)) . '</span>'
                : '<span class="badge badge-secondary">Sin prórroga</span>';
        }
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'activo',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {ramificar}',
        'vAlign' => 'middle',
        'width' => $columna5,

        'buttons' => [
            'view' => function ($url) {
                return Html::a('👁', $url, [
                    'class' => 'btn-action view',
                    'data-original-title' => 'Ver',
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url) {
                return Html::a('✏️', $url, [
                    'class' => 'btn-action edit',
                    'title' => 'Editar',
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'ramificar' => function ($url, $model) {
                $url = Url::to(['organismo_decreto/cargar_arbol', 'id' => $model->iddecreto]);

                return Html::a('🌳', $url, [
                    'class' => 'btn-action tree btn-ramificar-ajax',
                    'title' => 'Ramificar',
                ]);
            },
        ],
    ],

];
?>

<style>
    /* BOTONES ACCIÓN */
    .btn-action {
        display: inline-block;
        padding: 6px 8px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        transition: 0.2s;
        margin-right: 4px;
    }

    .btn-action:hover {
        transform: scale(1.15);
        opacity: 0.9;
    }

    .btn-action.view {
        color: #3498db;
    }

    .btn-action.edit {
        color: #f39c12;
    }

    .btn-action.tree {
        color: #27ae60;
    }

    /* BADGES */
    .badge {
        padding: 5px 10px;
        border-radius: 10px;
        font-size: 12px;
    }

    .badge-info {
        background: #d9edf7;
        color: #31708f;
    }

    .badge-secondary {
        background: #eee;
        color: #777;
    }
</style>
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>