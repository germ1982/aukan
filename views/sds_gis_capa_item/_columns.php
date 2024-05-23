<?php

use app\models\Sds_gis_capa;
use app\models\Sds_gis_capa_item;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcapa',
        'value' => function ($model) {
            $idcapa = $model->idcapa;
            if ($idcapa != null) {
                return Sds_gis_capa::findOne($idcapa)->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_gis_capa::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idcapa', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Capa...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'direccion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width'=>'5%',
        'format' => 'html',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'value' => function ($model) {
            $icon_class = 'fas fa-circle ';
            switch ($model->estado) {
                case Sds_gis_capa_item::ESTADO_VERDE:
                    $icon_class = $icon_class . 'text-success';
                    break;
                    case Sds_gis_capa_item::ESTADO_AMARILLO:
                    $icon_class = $icon_class . 'text-warning';
                    break;
                    case Sds_gis_capa_item::ESTADO_ROJO:
                    $icon_class = $icon_class . 'text-danger';
                    break;
            }
            return '<span class="'.$icon_class.'"></span>';
        },
        'filter' => ['1' => 'Bien', '2' => 'Regular'
        , '3' => 'Mal'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'post','data-pjax' => 0, 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'post','data-pjax' => 0, 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];
