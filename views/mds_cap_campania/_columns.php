<?php

use kartik\grid\GridView;
use yii\helpers\Url;

return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcampania',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'limite_inscripciones',
        'label'=>'Límite Inscripciones',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'value' => function ($model) {
            $estado = $model->estado;
            switch ($estado) {
                case 0:
                    return "No Activa";
                    break;
                case 1:
                    return "Activa";
                    break;
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        //'filter' => ArrayHelper::map(Mds_cap_capacitacion::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idcapacitacion', 'descripcion'),
        'filter' => ['0' => 'No Activa', '1' => 'Activa'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado'],
        'format' => 'raw',
        'width' => '20%',
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} ' . '{update} ',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
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
