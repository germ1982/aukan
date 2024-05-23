<?php

use app\models\View_relevamiento_planta;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'relevado',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array('No' => "No", 'Si' => "Si"),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'format' => 'raw',
        'width' => '5%',
    ],
    [
        'attribute' => 'ultima_modificacion',
        'label' => 'Modificado',
        'width' => '10%',
        'value' => function ($model) {
            if ($model->ultima_modificacion != null) {
                $fc = date_create($model->ultima_modificacion);
                $fc = date_format($fc, 'd/m/Y');
                return $fc;
            }
            return '';
        },
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'lugar_carga'
    ],
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
        'label' => 'DNI',
        'attribute' => 'documento',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'legajo',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'Cuil',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mail',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'edificio',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            View_relevamiento_planta::find()                
                ->groupBy('edificio')
                ->orderBy(['edificio' => SORT_ASC])->all(),
            'edificio',
            'edificio'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Edificio...'],
        'format' => 'raw',
        'width' => '20%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'Categoría',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Planta Permanente',
        'attribute' => 'lugar_planta_permanente',
        'value' => function ($model) {
            return $model->lugar_planta_permanente != null ? $model->lugar_planta_permanente : "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            View_relevamiento_planta::find()
                ->where('lugar_planta_permanente is not null')
                ->groupBy('lugar_planta_permanente')
                ->orderBy(['lugar_planta_permanente' => SORT_ASC])->all(),
            'lugar_planta_permanente',
            'lugar_planta_permanente'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Planta...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'attribute' => 'fecha_ingreso',
        'width' => '10%',
        'value' => function ($model) {
            if ($model->fecha_ingreso != null) {
                $fc = date_create($model->fecha_ingreso);
                $fc = date_format($fc, 'd/m/Y');
                return $fc;
            }
            return '';
        },
        'filter' => false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_nacimiento',
        'width' => '10%',
        'value' => function ($model) {
            if ($model->fecha_nacimiento != 'No Ingresada') {
                $fc = date_create($model->fecha_nacimiento);
                $fc = date_format($fc, 'd/m/Y');
                return $fc;
            }
            return $model->fecha_nacimiento;
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'funcion_actual',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observaciones',
        'value' => function ($model) {
            return $model->observaciones != null ? $model->observaciones : "";
        },
        'width' => '30%',
    ],
    /* [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'documento, $legajo' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false, for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'],
    ], */

];
