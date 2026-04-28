<?php

use kartik\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddecreto',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'periodo_inicio',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'periodo_final',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'periodo_prorroga',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'activo',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{view} {update} {ramificar}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Delete',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => '¿Está seguro?',
            'data-confirm-message' => '¿Seguro que desea eliminar este ítem?'
        ],

        'buttons' => [
            'ramificar' => function ($url, $model) {
                // Cambié la URL para que apunte a la acción del controlador, no directamente al archivo _form
                $url = Url::to(['organismo_decreto/cargar_arbol', 'id' => $model->iddecreto]);

                return Html::a('<span class="fa fa-tree"></span>', $url, [
                    'title' => 'Ramificar Estructura',
                    'data-toggle' => 'tooltip',
                    // QUITAMOS 'role' => 'modal-remote' para que no sea un modal
                    // AGREGAMOS una clase para manejarlo por JS si querés que cargue ahí mismo
                    'class' => 'btn-ramificar-ajax',
                ]);
            },
        ],
    ],

];
