<?php
use yii\helpers\Url;
$columna_1 = '5%';
$columna_2 = '0%';
$columna_3 = '80%';
$columna_4 = '5%';
$columna_5 = '5%';
$columna_6 = '5%';
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarticulo',
        'width' => $columna_1,
        'label' => 'ID',
        'filter' => false,  // Desactivar filtro
        //'enableSorting' => false,  // Desactivar ordenamiento
    ],
/*     [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'rubro',
        'width' => $columna_2,

    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'width' => $columna_3,

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ingresado',
        'width' => $columna_4,
        'filter' => false,  // Desactivar filtro
        //'enableSorting' => false,  // Desactivar ordenamiento

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'entregado',
        'width' => $columna_5,
        'filter' => false,  // Desactivar filtro
        //'enableSorting' => false,  // Desactivar ordenamiento
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'disponible',
        'width' => $columna_6,
        'filter' => false,  // Desactivar filtro
        //'enableSorting' => false,  // Desactivar ordenamiento
    ],
]
;   