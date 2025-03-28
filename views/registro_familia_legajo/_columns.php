<?php

use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$columna_1 = '8%';
$columna_2 = '15%';
$columna_3 = '10%';
$columna_4 = '15%';
$columna_5 = '25%';
$columna_6 = '20%';
$columna_7 = '7%';

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'num_legajo',
        'width' => $columna_1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_legajo',
        'value' => function ($model) {
            $id = $model->tipo_legajo;
            if ($id != null) {
                $configuracion = Configuracion::findOne($id);
                return "$configuracion->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::find()->where(['id_configuracion_tipo' => ConfiguracionTipo::TIPO_LEGAJO])->orderBy('descripcion')->all(), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'tipo legajo...'],
        'format' => 'raw',
        'width' => $columna_2,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'dni',
        'width' => $columna_3,
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'apellido',
        'width' => $columna_4,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
        'width' => $columna_5,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'archivo_adjunto',
        'width' => $columna_6,
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} ',
        'width' => $columna_7,
        'vAlign'=>'middle',
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