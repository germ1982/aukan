<?php

use app\models\Sds_com_provincia;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'codigo_postal',
        'width'=>'30px'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idprovincia',
        'label' => 'Provincia',
        'value' => function ($model) {
            $idprovincia = $model->idprovincia;
            if ($idprovincia != null) {
                $provincia = Sds_com_provincia::findOne($idprovincia);
                return $provincia->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Sds_com_provincia::find()->orderBy(['descripcion' => SORT_ASC])->all(),
            'idprovincia','descripcion'            
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Provincia...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'label' => 'Activo',
        'width' => '7%',
        'value' => function ($model) {
            if ($model->activo)
                return "Si";
            else
                return "No";
        },
        'filter' => ['0' => 'No', '1' => 'Si'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Consultar Datos','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Actualizar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   