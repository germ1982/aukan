<?php

use app\models\Configuracion;
use app\models\InformaticaIp;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\GridView as GridGridView;

/** @var \app\models\InformaticaIpSearch $searchModel */

$columna_1 = '7%'; //ip
$columna_2 = '10%'; //usada
$columna_3 = '45%'; //iddispositivo_red
$columna_4 = ''; //observacion
$columna_5 = '12%'; //mac
$columna_6 = '7%'; //mascara
$columna_7 = '7%'; //puerta_enlace
$columna_8 = '7%'; //dns
$columna_9 = '5%'; //accion

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ip',
        'width' => $columna_1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'usada',
        'label' => 'Usada',
        'format' => 'raw',
        'value' => function ($model) {
            $checked = $model->usada == 1 ? 'checked' : '';
            $url = Url::to(['toggle-usada', 'id' => $model->idip]);

            return '<input type="checkbox" class="chk-usada" ' . $checked . ' 
                   data-url="' . $url . '" 
                   style="cursor:pointer; transform: scale(1.2);">';
        },

        'filter' => [
            '1' => 'Usadas',
            '0' => 'Libres',
        ],
        'filterInputOptions' => ['prompt' => 'Todas', 'class' => 'form-control'],
        'width' => $columna_2,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'observacion',
        'format' => 'raw',
        'label' => 'Detalle',
        'value' => function ($model) {
            $info = InformaticaIp::get_dispositivo_red($model->iddispositivo_red);
            $obsDispositivo = $info['observacion'] ?? '';
            $idDispositivo = $info['iddispositivo'] ?? null;

            $textoCompleto = trim("$obsDispositivo $model->observacion");

            if (empty($textoCompleto)) {
                return '';
            }

            if (!$idDispositivo) {
                return $textoCompleto;
            }

            return \yii\helpers\Html::a(
                $textoCompleto,
                ['organismo_dispositivo/view', 'id' => $idDispositivo],
                [
                    'title' => 'Ver dispositivo',
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                ]
            );
        },
        'filter' => false,
        'width' => $columna_3,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mac',
        'value' => function ($model) {
            return $model->mac ?? '';
        },
        'filter' => false,
        'width' => $columna_5,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mascara',
        'value' => function ($model) {
            return Configuracion::findOne($model->mascara) ? Configuracion::findOne($model->mascara)->descripcion : '';
        },
        'filter' => false,
        'width' => $columna_6,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'puerta_enlace',
        'value' => function ($model) {
            return Configuracion::findOne($model->puerta_enlace) ? Configuracion::findOne($model->puerta_enlace)->descripcion : '';
        },
        'filter' => false,
        'width' => $columna_7,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dns',
        'value' => function ($model) {
            return Configuracion::findOne($model->dns) ? Configuracion::findOne($model->dns)->descripcion : '';
        },
        'filter' => false,
        'width' => $columna_8,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'width' => $columna_9,
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
            'data-method' => false,
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],
];