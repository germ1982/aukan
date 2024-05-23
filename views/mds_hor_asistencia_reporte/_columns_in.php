<?php

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'legajo',
        'label' => 'Legajo',       
        'filter' => false,
        'width' => '12%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'empleado',
        'label' => 'Empleado',       
        'filter' => false,
        'width' => '40%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'pr_categoria',
        'label' => 'PR',       
        'filter' => false,
        'width' => '5%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dia',
        'label' => 'Días',
        'filter' => false,
        'width' => '43%',
        'hAlign' => 'center'
    ],
];
