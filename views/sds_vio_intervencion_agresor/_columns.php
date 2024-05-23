<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
        'label' => 'Apellido',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'label' => 'Nombre',
        'enableSorting' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'label' => 'DNI',
        'enableSorting' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'parentezco',
        'label' => 'Parentesco',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update} {eliminar}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'idintervencion, $idagresor' => $key]);
        },
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/update', 'id' => $model['idagresor'], 'llamadoDesdeModal' => true, 'idintervencion' => $model['idintervencion']]);
                return  Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'data-pjax' => 1, 'role' => 'modal-remote',
                    'title' => 'Editar', 'data-toggle' => 'tooltip'
                ]);
            },
            'eliminar' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_intervencion_agresor/delete', 'idintervencion' => $model['idintervencion'], 'idagresor' => $model['idagresor']]);
                //  Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_intervencion_agresor', $model->idagresor, $model->getAttributes());
                return  Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Eliminar Agresor de Intervención',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-pjax' => 1,
                    'title' => ('Borrar'),
                    'data' => [
                        'confirm' => '¿Está seguro que desea eliminar este elemento?',
                        'method' => 'post',
                    ],
                ]);
            },
            // 'view' => function ($url, $model) {
            //     $url =  Url::to(['/sds_vio_agresor/view', 'id' => $model['idagresor']]);
            //     return  Html::a(' <span class= "glyphicon glyphicon-eye-open"></span>', $url, [
            //         'data-pjax' => 0, 
            //         'title' => 'Ver (en nueva pestaña)', 'data-toggle' => 'tooltip',
            //         'target' => '_blank'
            //     ]);
            // }
            'view' => function ($url, $model) {
                $url =  Url::to(['/sds_vio_agresor/view', 'id' => $model['idagresor'], 'idintervencion' => $model['idintervencion'], 'llamadoDesdeModal' => true]);
                return  Html::a(' <span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'data-pjax' => 1,
                    'role' => 'modal-remote',
                    'title' => 'Ver',
                    'data-toggle' => 'tooltip',
                ]);
            }
        ],
    ],
];
