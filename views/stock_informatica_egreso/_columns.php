<?php

use app\models\Empleado;
use app\models\Persona;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$columna_1 = '5%';
$columna_2 = '10%';
$columna_3 = '20%';
$columna_4 = '20%';
$columna_5 = '20%';
$columna_6 = '20%';
$columna_7 = '5%';

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idegreso',
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
        'attribute' => 'idpersona_solicitante',
        'value' => function ($model) {
            if ($model->idpersona_solicitante) {
                $persona = Persona::get_persona_ayn($model->idpersona_solicitante);
                return "$persona";
            }
            return "";
        },
        'width' => $columna_3,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado_autorizacion',
        'value' => function ($model) {
            if ($model->idempleado_autorizacion) {
                $empleado = Empleado::get_empleado($model->idempleado_autorizacion);
                return "$empleado->descripcion";
            }
            return "";
        },
        'width' => $columna_4,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado_despacha',
        'value' => function ($model) {
            if ($model->idempleado_despacha) {
                $empleado = Empleado::get_empleado($model->idempleado_despacha);
                return "$empleado->descripcion";
            }
            return "";
        },
        'width' => $columna_5,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpersona_recibe',
        'value' => function ($model) {
            if ($model->idpersona_recibe) {
                $persona = Persona::get_persona_ayn($model->idpersona_recibe);
                return "$persona";
            }
            return "";
        },
        'width' => $columna_6,
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'width' => $columna_7,
        'template' => '{view} {update} {imprimir_acta_entrega}',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Eliminar',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
        'buttons' => [
            'imprimir_acta_entrega' => function ($url, $model) {
                $url =  Url::to(['/stock_informatica_egreso/imprimir_acta_entrega', 'idegreso' => $model->idegreso]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'title' => "Imprimir ",
                    'role' => 'post',
                    'data-pjax' => 0,
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
    ],

];
