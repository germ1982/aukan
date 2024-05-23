<?php

use yii\helpers\Html;
use yii\helpers\Url;


return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'value' => function ($data) {
            return mb_strtoupper($data['nombre']) . ' ' . mb_strtoupper($data['apellido']);
        }
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'editar',
        'label' => '¿Puede editar?',
        'value' => function ($data) {
            return $data['editar'] ? 'Si' : 'No';
        }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{update} {eliminar}',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/mds_cor_intervencion_usuario/update', 'id' => $model['id']]);
                return  Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip']);
            },
            'eliminar' => function ($url, $model) {
                $url =  Url::to(['/mds_cor_intervencion_usuario/delete', 'id' => $model['id']]);
                return  Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Eliminar',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post'
                ]);
            },
        ],
    ],
];
