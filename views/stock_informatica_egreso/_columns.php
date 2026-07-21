<?php

use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\StockInformaticaEgreso\SearchModel $searchModel */


$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$columna_1 = '5%';
$columna_2 = '10%';
$columna_3 = '15%';
$columna_4 = '15%';
$columna_5 = '15%';
$columna_6 = '15%';
$columna_7 = '20%';
$columna_8 = '5%';

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
        'attribute' => 'solicitante',
        'value' => function ($model) {
            if ($model->personaSolicitante) {
                return $model->personaSolicitante->apellido . ', ' . $model->personaSolicitante->nombre;
            }
            return '';
        },
        'width' => $columna_3,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'autorizacion',
        'value' => function ($model) {
            return ($model->empleadoAutorizacion && $model->empleadoAutorizacion->persona)
                ? $model->empleadoAutorizacion->persona->apellido . ' ' . $model->empleadoAutorizacion->persona->nombre : '';
        },
        'filter' => Html::activeTextInput($searchModel, 'autorizacion', ['class' => 'form-control']),
        'width' => $columna_4,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'despachante',
        'value' => function ($model) {
            return ($model->empleadoDespacha && $model->empleadoDespacha->persona)
                ? $model->empleadoDespacha->persona->apellido . ' ' . $model->empleadoDespacha->persona->nombre : '';
        },
        'filter' => Html::activeTextInput($searchModel, 'despachante', [
                'class' => 'form-control',
            ]),
        'width' => $columna_5,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'receptor',
        'value' => function ($model) {
            return ($model->personaReceptor)
                ? $model->personaReceptor->apellido . ' ' . $model->personaReceptor->nombre : '';
        },
        'filter' => Html::activeTextInput($searchModel, 'receptor', [
                'class' => 'form-control',
            ]),
        'width' => $columna_6,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id_dispositivo_destino',
        'value' => function ($model) {
            return $model->id_dispositivo_destino ? OrganismoDispositivo::get_dispositivo($model->id_dispositivo_destino)->descripcion:'';
            
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'dispositivo...'],
        'format' => 'raw',
        'width' => $columna_7,
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'width' => $columna_8,
        'template' => '{view} {update}{imprimir_acta_entrega_2026}',
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
        

        
            'imprimir_acta_entrega_2026' => function ($url, $model) {
                $url =  Url::to(['/stock_informatica_egreso/imprimir_acta_entrega_2026', 'idegreso' => $model->idegreso]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'title' => "Imprimir Acta2026 ",
                    'role' => 'post',
                    'data-pjax' => 0,
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },],
        
    ],

];
