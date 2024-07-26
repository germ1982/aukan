<?php

use yii\helpers\Url;

return [

      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'nombre',
            'width' => '60%',
      ],


      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'orden',
            'width' => '10%',
      ],

      [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'activo',
            'value' => function ($model) {
                  return $model->activo == 1 ? 'Si' : 'No';
            },
            'width' => '10%',
            'filter' => ['0' => 'No', '1' => ' Si'],
      ],
      [
            'class' => 'kartik\grid\ActionColumn',
            'width' => '20%',
            'template' => '{view} {update} ',
            'dropdown' => false,
            'vAlign' => 'middle',
            'urlCreator' => function ($action, $model, $key, $index) {
                  return Url::to([$action, 'id' => $key]);
            },
            'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
            'deleteOptions' => [
                  'role' => 'modal-remote', 'title' => 'Eliminar',
                  'data-confirm' => false, 'data-method' => false, // for overide yii data api
                  'data-request-method' => 'post',
                  'data-toggle' => 'tooltip',
                  'data-confirm-title' => 'Are you sure?',
                  'data-confirm-message' => 'Are you sure want to delete this item'
            ],
      ],

];
