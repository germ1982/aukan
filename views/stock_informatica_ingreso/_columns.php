<?php

use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\Persona;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$columna_1 = '5%';
$columna_2 = '15%';
$columna_3 = '15%';
$columna_4 = '15%';
$columna_5 = '15%';
$columna_6 = '10%';

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idingreso',
        'width' => $columna_1,
    ],
    [
        'attribute' => 'fecha',
        'width' => $columna_2,
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde',
            'attribute2' => 'fhasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorigen',
        'value' => function ($model) {
            $id = $model->idorigen;
            if ($id != null) {
                $tipo = Configuracion::findOne($id);
                return "$tipo->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::find()->where(['id_configuracion_tipo' => ConfiguracionTipo::STOCK_ORIGEN])->orderBy('descripcion')->all(), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Origen...'],
        'format' => 'raw',
        'width' => $columna_3,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'origen_referencia',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado_recepcion',
        'value' => function ($model) {
            if ($model->idempleado_recepcion) {
                $empleado = Empleado::get_empleado($model->idempleado_recepcion);
                return "$empleado->descripcion";
            }
            return "";
        },
        'width' => $columna_4,
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'idusuario_carga',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'observacion',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} ',
        'width' => $columna_5,
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