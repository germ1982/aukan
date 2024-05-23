<?php

use app\models\mds_ans_alimentar_titulares;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cuil',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'provincia',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'municipio',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'localidad',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'departamento',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'totalHijos',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'embarazo',
        'hAlign'=>'center',
        'value' => function ($model) {
            return $model->embarazo != '0' ? 'SI' : 'NO';
        },
        'width' => '8%',
        'filter' => ['0' => 'NO', '1' => ' SI'],
        'width'=>'8%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_hora',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado_entrega',
        'value' => function ($model) {
            switch ($model->estado_entrega) {
                case mds_ans_alimentar_titulares::PENDIENTE:
                    return "Pendiente";
                case mds_ans_alimentar_titulares::ENTREGADA:
                    return "Entregada";
                default:
                    return "";
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            mds_ans_alimentar_titulares::PENDIENTE => "Pendiente",
            mds_ans_alimentar_titulares::ENTREGADA => "Entregada"
        ],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'width' => '20%',
    ],
//   [
//       'class' => '\kartik\grid\DataColumn',
//       'attribute' => 'estado',
//       'value' => function ($model) {
//           switch ($model->estado) {
//               case mds_ans_alimentar_titulares::activo:
//                   return "Activo";
//               case mds_ans_alimentar_titulares::alta:
//                   return "Alta";
//                default:
//                  return "";
//            }
//        },
//        'filterType' => GridView::FILTER_SELECT2,
//        'filter' => [
//            mds_ans_alimentar_titulares::activo => "activo",
//            mds_ans_alimentar_titulares::alta => "alta"
//        ],
//        'filterWidgetOptions' => [
//           'pluginOptions' => ['allowClear' => true],
//        ],
//        'filterInputOptions' => ['placeholder' => 'Estado...'],
//        'width' => '20%',
//    ],
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
