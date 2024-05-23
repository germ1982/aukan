<?php

use yii\helpers\Html;
use yii\helpers\Url;

return [
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idconfiguraciontipo',
        'width'=>'5%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class' => '\kartik\grid\BooleanColumn',
        'trueLabel' => 'Si',
        'falseLabel' => 'No',
        'attribute' => 'activo',
        'useSelect2Filter' => 'Activo...',
        'value' => function ($model) {
            if ($model->activo) {
                return true;
            } else {
                return false;
            }
        },
        'width' => '5%',
        'filterInputOptions' => ['placeholder' => 'Activo'],
    ],




    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'template'=>'{update} {activar}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons'=>[
            'activar' => function ($url, $model) {
                $url =  Url::to([
                    'update', 'id' => $model->idconfiguraciontipo,'estado'=>($model->activo?0:1)
                ]);
                return Html::a('<span class= "glyphicon glyphicon-'.($model->activo?"remove":"ok").'"></span>', $url, [
                    'title' => ($model->activo?"Desactivar":"Activar"),
                    'role' => 'modal-remote', 'data-pjax' => 1, 'target' => '',
                    //'data-toggle' => 'tooltip',
                ]);
            },
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   