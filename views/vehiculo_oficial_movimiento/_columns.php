<?php

use app\models\Empleado;
use app\models\VehiculoOficial;
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
$columna_2 = '14%';
$columna_3 = '10%';
$columna_4 = '17%';
$columna_5 = '17%';
$columna_6 = '17%';
$columna_7 = '17%';
$columna_8 = '5%';


return [


    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_1,
        'attribute'=>'idmovimiento',
        'label'=>'ID',
        
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
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'hora',
        'width' => $columna_3,

    ],
   

    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_4,
        'attribute'=>'idvehiculo',
        'value' => function ($model) {

            $vehiculo = VehiculoOficial::getVehiculoOficial($model->idvehiculo);

            return "$vehiculo";
        },

        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(VehiculoOficial::getVehiculosOficiales(), 'idvehiculo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'vehiculo...'],
        'format' => 'raw',
    ],
    
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_5,
        'attribute'=>'chofer',
        'value' => function ($model) {

            $choferInformacion = Empleado::get_empleado($model->chofer)->descripcion;

            return "$choferInformacion";
        },

        
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Empleado::get_empleado_choferes(),'idempleado', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'chofer...'],
        'format' => 'raw',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_6,
        'attribute'=>'lugar_salida',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'width' => $columna_7,
        'attribute'=>'lugar_destino',
    ],
    


    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'width' => $columna_8,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   