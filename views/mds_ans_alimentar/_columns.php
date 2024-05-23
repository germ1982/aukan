<?php

use app\models\mds_ans_alimentar;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'value' => function ($model) {
            switch ($model->estado) {
                case mds_ans_alimentar::PENDIENTE:
                    return "Pendiente";
                case mds_ans_alimentar::ENTREGADA:
                    return "Entregada";

                default:
                    return "";
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            mds_ans_alimentar::PENDIENTE => "Pendiente",
            mds_ans_alimentar::ENTREGADA => "Entregada"
        ],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'width' => '20%',
    ],
    // [
    //      'class'=>'\kartik\grid\DataColumn',
    //      'attribute'=>'cuil',
    //  ],

    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'fecha',
    // ],
   // [
    //    'class' => '\kartik\grid\DataColumn',
    //    'attribute' => 'municipio',
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'post', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'post', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];
