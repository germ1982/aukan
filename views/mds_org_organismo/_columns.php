<?php

use app\models\Mds_org_organismo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'width' => '35%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'padre',
        'value' => function ($model) {
            $idpadre = $model->padre;
            if ($idpadre != null) {
                $organismo_padre = Mds_org_organismo::findOne($idpadre);
                return $organismo_padre->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'abreviatura',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nivel',
        'width' => '5%',
        'filter' => [1 => '1', 2 => '2', 3 => '3', 4 => '4']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            return $model->activo == 1 ? 'Si' : 'No';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],
    [
        'class' => 'kartik\grid\ActionColumn', 
        'template' => '{view} {update}',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Borrar Organismo',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Está Seguro?',
            'data-confirm-message' => 'Está seguro que desea eliminar el Organismo'
        ],
        'width' => '12%',
    ],

];
