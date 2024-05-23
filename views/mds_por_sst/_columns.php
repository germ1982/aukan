<?php

use yii\helpers\Url;

return [
    /*[
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],*/

    /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asiento',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cheque',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cantidad',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha',
    ],*/
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'width' => '8%',       
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'width' => '31%',
        'value' => function ($model) {
            return $model->nombre . '  ' . $model->apellido;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'referente',
        'width' => '20%',      
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'autorizo',
        'width' => '20%',      
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'monto',
        'width' => '8%',      
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'destino',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'localidad',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'grupo',
    // ],
   /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'referente',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'pago',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'autorizo',
    ],*/
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'observacion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'situacion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'retira_cheque',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mes',
        'width' => '5%',      
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'anio',
        'width' => '8%',      
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'sexo',
    // ],

    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'liquidacion_anterior',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view}',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'post', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
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
