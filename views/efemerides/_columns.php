<?php
use yii\helpers\Url;

$columna_1 = '10%'; // ID
$columna_2 = '15%'; // Fecha
$columna_3 = '25%'; // Título
$columna_4 = '25%'; // Descripción
$columna_5 = '8%';  // Activo
$columna_6 = '7%';  // ¿Regla?
$columna_7 = '5%';  // Fotos
$columna_8 = '5%';  // Acciones

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idefemeride',
        'headerOptions' => ['style' => 'width:' . $columna_1],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha',
        'headerOptions' => ['style' => 'width:' . $columna_2],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'titulo',
        'headerOptions' => ['style' => 'width:' . $columna_3],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
        'headerOptions' => ['style' => 'width:' . $columna_4],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'headerOptions' => ['style' => 'width:' . $columna_5],
        'value' => function ($model) {
            return $model->activo == 1 ? 'Sí' : 'No';
        },
        'filter' => ['0' => 'No', '1' => 'Sí'],
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Regla',
        'headerOptions' => ['style' => 'width:' . $columna_6],
        'value' => function ($model) {
            $tieneRegla = (new \yii\db\Query())
                ->from('efemerides_reglas')
                ->where(['idefemeride' => $model->idefemeride])
                ->exists();
            return $tieneRegla ? 'Sí' : 'No';
        },
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Fotos',
        'headerOptions' => ['style' => 'width:' . $columna_7],
        'value' => function ($model) {
            $cantidad = (new \yii\db\Query())
                ->from('efemerides_fotos')
                ->where(['idefemeride' => $model->idefemeride])
                ->count();
            return '<span class="badge bg-blue">' . $cantidad . '</span>';
        },
        'format' => 'raw',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'headerOptions' => ['style' => 'width:' . $columna_8],
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => '{view} {update}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],
];