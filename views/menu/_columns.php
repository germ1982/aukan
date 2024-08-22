<?php

use yii\helpers\Url;
use app\models\Menu;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;

return [

      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'title',
            'width' => '20%',
      ],
      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'orden',
            'value' => function ($model) {
                  return $model->orden == 1 ? $model->orden : $model->orden .' '. Html::a('<span class= "fa fa-arrow-circle-up"></span>', ['menu/subir', 'id' => $model->id], [
                        'role' => 'modal-remote',
                        //'class' => 'btn neon btn-xs',
                        'data-confirm' => false,
                        'data-method' => false,
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'title' => 'Subir'
                  ]);
            },
            'format' => 'raw',
            'width' => '7%',
      ],
      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'icon_yii',
      ],
      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'link_yii',
      ],

      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'padre',
            'value' => function ($model) {
                  $idpadre = $model->padre;
                  if ($idpadre == 0) return 'Raiz';
                  if ($idpadre != null) {
                        $padre = Menu::findOne($idpadre);
                        return $padre->title;
                  }
                  return "";
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Menu::find()->orderBy(['title' => SORT_ASC])->all(), 'id', 'title'),
            'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Padre...'],
            'format' => 'raw',
            'width' => '20%',
      ],

      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'activo',
            'value' => function ($model) {
                  return $model->activo == 1 ? 'Si' : 'No';
            },
            'width' => '7%',
            'filter' => ['0' => 'No', '1' => ' Si'],
            'width' => '7%',
      ],

      [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'template' => '{view} {update} ',
            'urlCreator' => function ($action, $model, $key, $index) {
                  return Url::to([$action, 'id' => $key]);
            },
            'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
            'deleteOptions' => [
                  'role' => 'modal-remote',
                  'title' => 'Eliminar',
                  'data-confirm' => false,
                  'data-method' => false, // for overide yii data api
                  'data-request-method' => 'post',
                  'data-toggle' => 'tooltip',
                  'data-confirm-title' => 'Esta Seguro?',
                  'data-confirm-message' => 'Esta seguro que quiere eliminar este item?'
            ],
      ],

];
