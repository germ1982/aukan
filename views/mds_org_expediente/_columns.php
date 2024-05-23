<?php

use app\models\Mds_org_organismo;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

//$columna1 = '4%';//idexpediente
$columna2 = '12%';//fecha_ingreso
$columna3 = '10%';//expediente
$columna4 = '10%';//gde
$columna5 = '13%';//causante
$columna6 = '10%';//extracto
$columna7 = '10%';//pedido_numero
$columna8 = '13%';//destino
$columna9 = '12%';//fecha_salida
//$columna10 = '12%';//idorganismo
$columna11 = '10%';//actions

return [

    /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idexpediente',
        'label' => 'Id',
        'width' => $columna1,
    ], */
    [
        'attribute' => 'fecha_ingreso',
        'width' => $columna2,
        'label' => 'Fecha Ingreso',
        'value' => function ($model) {
            $fc = date_create($model->fecha_ingreso);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fidesde',
            'attribute2' => 'fihasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => false,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),

    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'expediente',
        'width' => $columna3,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'gde',
        'width' => $columna4,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'causante',
        'width' => $columna5,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'extracto',
        'width' => $columna6,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'pedido_numero',
        'label' => 'Pedido Nro.',
        'width' => $columna7,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'destino',
        'width' => $columna8,
    ],
    
    [
        'attribute' => 'fecha_salida',
        'width' => $columna9,
        'label' => 'Fecha Salida',
        'value' => function ($model) {
            $fc = date_create($model->fecha_salida);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fsdesde',
            'attribute2' => 'fshasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => false,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),

    ],

/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idorganismo',
    ], */
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'label' => 'Organismo',
        'value' => function ($model) {
            if ($model->idorganismo != null) {
                $organismo = Mds_org_organismo::findOne($model->idorganismo);
                return $organismo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_organismo::find()->where("idorganismo IN (SELECT mds_org_expediente.idorganismo from mds_org_expediente)")->orderBy(['descripcion' => SORT_ASC])->all(), 
            'idorganismo','descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => $columna10,
        'label' => 'Organismo'
    ], */
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'width' => $columna11,
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,//for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta Seguro?',
                          'data-confirm-message'=>'Esta seguro que desea eliminar'], 
    ],

];   