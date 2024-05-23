<?php
use yii\helpers\Url;
use kartik\grid\GridView;

return [

        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iddeposito',
        'label' => 'Id',
        'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
        'width' => '70%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            if ($model->activo==1)
                return "Si";
            else
                return "No";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array('0'=>"No",'1'=>"Si",' '=>"Ambos"),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '...'],
        'format' => 'raw',
        'width' => '10%',
    ],
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idorganismo',
    ], */
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'width' => '10%',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   