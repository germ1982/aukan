<?php

use app\models\Edificio;
use app\models\EdificioOficina;
use app\models\Organismo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$mysql_oficinas = "SELECT o.idoficina as idoficina,
                    CONCAT(e.descripcion_fija,' - ', e.descripcion_gestion,' - ', o.descripcion) as descripcion
                    FROM edificio_oficina o
                    JOIN edificio e on o.idedificio = e.idedificio";

$columna01 = "5%";
$columna02 = "25%";
$columna03 = "10%";
$columna04 = "25%";
$columna05 = "5%";
$columna06 = "5%";
$columna07 = "5%";
$columna08 = "5%";
$columna09 = "5%";
$columna10 = "5%";

return [

    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'width' => $columna01,
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
        'width' => $columna02,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'alias',
        'width' => $columna03,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idoficina',
        'value' => function ($model) {
            $id = $model->idoficina;
            if ($id != null) {
                $oficina = EdificioOficina::findOne($id);
                $edificio = Edificio::findOne($oficina->idedificio);
                return "$edificio->descripcion_fija - $edificio->descripcion_gestion - $oficina->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(EdificioOficina::findBySql($mysql_oficinas)->all(), 'idoficina', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo de Dato...'],
        'format' => 'raw',
        'width' => $columna04,
    ],
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'organismo',
        'value' => function ($model) {
            return Organismo::findOne($model->idorganismo)->descripcion;
        },
        'width' => $columna04,
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'es_oficial',
        'value' => function ($model) {
            return $model->es_oficial == 1 ? 'Si' : 'No';
        },
        'filter' => ['0' => 'No', '1' => ' Si'],
        'width' => $columna05,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'es_organismo',
        'value' => function ($model) {
            return $model->es_organismo == 1 ? 'Si' : 'No';
        },
        'filter' => ['0' => 'No', '1' => ' Si'],
        'width' => $columna06,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
        'width' => $columna07,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            return $model->activo == 1 ? 'Si' : 'No';
        },
        'filter' => ['0' => 'No', '1' => ' Si'],
        'width' => $columna08,
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'width' => $columna09,
        'vAlign' => 'middle',
        'template' => '{view} {update} ',
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
    ],

];
