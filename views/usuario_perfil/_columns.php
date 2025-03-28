<?php

use app\models\ConfiguracionTipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_configuracion',
        'width' => '10%',
    ],

   /*  [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_configuracion_tipo',
        'value' => function ($model) {
            $id = $model->id_configuracion_tipo;
            if ($id != null) {
                $tipo = ConfiguracionTipo::findOne($id);
                return "$tipo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(ConfiguracionTipo::find()->where(['activo'=>1])->orderBy('descripcion')->all(), 'id_configuracion_tipo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo de Dato...'],
        'format' => 'raw',
    ], */
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
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
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => '{view} {update} ',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta Seguro?',
                          'data-confirm-message'=>'Esta seguro de eliminar este item?'], 
    ],

];   