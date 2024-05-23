<?php

use app\models\Mds_seg_item;
use yii\helpers\Html;
use yii\helpers\Url;

return [    
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'filter'=> false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iditem',
        'value' => function ($model) {            
            return Mds_seg_item::findOne($model->iditem)->descripcion;
        },
        'filter'=> false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'alta',
        'value' => function ($model) {
            return $model->alta == 1 ? 'Si' : 'No';
        },
        'width' => '10%',
        'filter'=> false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'baja',
        'value' => function ($model) {
            return $model->baja == 1 ? 'Si' : 'No';
        },
        'width' => '10%',
        'filter'=> false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'modifica',
        'value' => function ($model) {
            return $model->modifica == 1 ? 'Si' : 'No';
        },
        'width' => '10%',
        'filter'=> false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ver',
        'value' => function ($model) {
            return $model->ver == 1 ? 'Si' : 'No';
        },
        'width' => '10%',
        'filter'=> false
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{update} {eliminar}',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'buttons' => [
            'eliminar' => function ($url, $model) {
                $url =  Url::to(['/mds_seg_permiso/eliminar', 'id' => $model->idpermiso]);
                return  Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Eliminar',
                    'data-toggle' => 'tooltip'
                ]);
            },
        ],
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        
    ],

];
