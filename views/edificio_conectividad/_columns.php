<?php

use app\models\Edificio;
use yii\helpers\Url;
use app\models\OrganismoDispositivo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;

$columna1 = "50%";
$columna2 = "10%";
$columna3 = "15%";
$columna4 = "10%";
$columna5 = "10%";
$columna6 = "5%";
return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idedificio',
        'value' => function ($model) {
            return Edificio::get_edificio_descripcion($model->idedificio);
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Edificio::get_edificios_con_conexion(), 'idedificio', 'descripcion_fija'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Edificio...'],
        'format' => 'raw',
        'width' => $columna1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'infraestructura',
        'value' => function ($model) {
            return Configuracion::findOne($model->infraestructura)->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_CONECTIVIDAD_SERVICIO), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Infraestructura...'],
        'format' => 'raw',
        'width' => $columna2,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'servicio',
        'value' => function ($model) {
            return Configuracion::findOne($model->servicio)->descripcion . " $model->velocidad_en_mb Mbps";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_CONECTIVIDAD_SERVICIO), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Servicio...'],
        'format' => 'raw',
        'width' => $columna3,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_conexion',
        'value' => function ($model) {
            return Configuracion::findOne($model->tipo_conexion)->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_CONECTIVIDAD_TIPO_CONEXION), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Conexion...'],
        'format' => 'raw',
        'width' => $columna4,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'value' => function ($model) {
            return Configuracion::findOne($model->estado)->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_CONECTIVIDAD_ESTADO), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'format' => 'raw',
        'width' => $columna5,
    ],
/*     [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observacion',
    ], */

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Delete',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
        'width' => $columna6,
    ],

];
