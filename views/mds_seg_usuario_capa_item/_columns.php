<?php

use app\models\Sds_gis_capa;
use app\models\Sds_gis_capa_item;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcapaitem',
        'value' => function ($model) {
            $idcapaitem = $model->idcapaitem;
            if ($idcapaitem != null) {
                $capaitem = Sds_gis_capa_item::findOne($idcapaitem);
                return $capaitem->descripcion;
            }
            return "";
        },
        'filter' => false,
        'format' => 'raw',
        'width' => '85%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{delete}',
        'width' => '10%',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
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
