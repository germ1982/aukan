<?php

use Codeception\Util\Template;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$columana1='10%';
$columana2='40%';
$columana3='40%';
$columana4='10%';
return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo_descripcion',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filter['htipo'],        
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Habilitación'],
        'width' => $columana1
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'detalle',
        'width' => $columana2
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'vencimiento',
        'value'=>function($model){
            return date('d/m/Y', strtotime($model->vencimiento));
        },
        'width' => $columana3
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{update} {adjunto}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'buttons' =>[
            'adjunto' => function ($url, $model) {
                $url =  Url::to('uploads/veh_habilitacion/veh_'.$model->idvehiculo.'/'.$model->adjunto);
                if($model->adjunto!=null){
                    return Html::a('<i class="fas fa-file"></i>', $url, [
                        'title' => 'Ver adjunto',
                        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                        'data-toggle' => 'tooltip',
                    ]);
                }
                return ' ';
            }
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
        'width' => $columana4
    ],

];   