<?php

use app\models\Mds_org_novedad;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Url;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idnovedad',
        'label' =>'Id',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'titulo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'attribute' => 'fechahora',
        'width' => '10%',
        'label' => 'Fecha Hora',
        'value' => function ($model) {
            $fc = date_create($model->fechahora);
            $fc = date_format($fc, 'd/m/Y H:i:s');
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
        'attribute' => 'estado',
        'value' => function ($model) {
            switch ($model->estado) {
                case Mds_org_novedad::NO_PUBLICADO:
                    return "No Publicado";
                case Mds_org_novedad::PUBLICADO:
                    return "Publicado";

                default:
                    return "";
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [
            Mds_org_novedad::NO_PUBLICADO => "No Publicado",
            Mds_org_novedad::PUBLICADO => "Publicado"
        ],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'width' => '20%',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tipo',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Borrar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Eliminar Novedad',
                          'data-confirm-message'=>'¿Realmente desea eliminar la novedad?'], 
    ],

];   