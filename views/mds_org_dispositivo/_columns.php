<?php

use app\models\Mds_org_organismo;
use app\models\Sds_gis_capa_item;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'descripcion',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idorganismo',
        'value' => function ($model) {
            $idorganismo = $model->idorganismo;
            if ($idorganismo != null) {
                $organismo = Mds_org_organismo::findOne($idorganismo);
                return $organismo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Organismo...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcapaitem',
        'value' => function ($model) {
            $idcapaitem = $model->idcapaitem;
            if ($idcapaitem != null) {
                $edificio = Sds_gis_capa_item::findOne($idcapaitem);
                return $edificio->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_gis_capa_item::find()->where("idcapa<>1")->orderBy(['descripcion' => SORT_ASC])->all(), 'idcapaitem', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Edificio...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'value' => function ($model) {
            return $model->activo == 1 ? 'Si' : 'No';
        },
        'width' => '8%',
        'filter' => ['0' => 'No', '1' => ' Si']
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => ' {view} {update} {delete}',
        'vAlign' => 'middle',
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/mds_org_dispositivo/view', 'id' => $model->iddispositivo]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url, $model) {
                $url =  Url::to(['/mds_org_dispositivo/update', 'id' => $model->iddispositivo]);
                return Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'deleteOptions' => [
                'role' => 'modal-remote', 'title' => 'Eliminar',
                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                'data-request-method' => 'post',
                'data-toggle' => 'tooltip',
                'data-confirm-title' => '¿Está segura/o?',
                'data-confirm-message' => '¿Está segura/o de querer eliminar el dispositivo?'
            ],
        ]
    ],

];
