<?php

use app\models\Organismo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'padre',
        'value' => function ($model) {
              $idpadre = $model->padre;
              if ($idpadre == 0) return 'Raiz';
              if ($idpadre != null) {
                    $padre = Organismo::findOne($idpadre);
                    return $padre->descripcion;
              }
              return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::merge(
              ['0' => 'Raiz'], // Agrega manualmente la opción "Raiz" con id 0
              ArrayHelper::map(Organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismo', 'descripcion')
          ),
        'filterWidgetOptions' => [
              'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Padre...'],
        'format' => 'raw',
        'width' => '25%',
  ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nivel',
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'width' => '5%',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'abreviatura',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} ',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Delete',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];
